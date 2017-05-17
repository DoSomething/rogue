<?php

namespace Rogue\Providers;

use Rogue\Models\Post;
use Rogue\Models\Review;
use Rogue\Models\Signup;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // When Signups are saved create an event for them.
        Signup::saved(function ($signup) {
            $signup->events()->create([
                // @TODO - Should this just be the attributes that have changed? Or the whole thing?
                'content' => $signup->toJson(),
            ]);
        });

        // When Posts are saved create an event for them.
        Post::saved(function ($post) {
            $post->events()->create([
                'content' => $post->toJson(),
            ]);
        });

        // When Reviews are saved create an event for them.
        Review::saved(function ($review) {
            $review->events()->create([
                'content' => $review->toJson(),
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
