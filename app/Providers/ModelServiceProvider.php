<?php

namespace Rogue\Providers;

use Rogue\Models\Post;
use Rogue\Models\Event;
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
        // When a Signup's why_participated, quantity, quantity_pending, or deleted_at are changed, create an event.
        // @TODO: when we move quantity on the post, we'll want to remove this check below.
        Signup::saving(function ($signup) {
            if ($signup->isDirty('why_participated') || $signup->isDirty('quantity') || $signup->isDirty('quantity_pending') || $signup->isDirty('deleted_at')) {
                $signup->events()->create([
                    'content' => $signup->toJson(),
                    // Use the authenticated user if coming from the web,
                    // otherwise use the id of the user in the request.
                    'user' => auth()->user() ? auth()->user()->northstar_id : $signup->northstar_id,
                ]);
            }
        });

        // When Posts are saved create an event for them.
        Post::saved(function ($post) {
            $post->events()->create([
                // @TODO: this should include the tags with the post
                'content' => $post->toJson(),
                // Use the authenticated user if coming from the web,
                // otherwise use the id of the user in the request.
                'user' => auth()->user() ? auth()->user()->northstar_id : $post->northstar_id,
            ]);
        });

        // When Reviews are saved create an event for them.
        Review::saved(function ($review) {
            $review->events()->create([
                'content' => $review->toJson(),
                'user' => $review->admin_northstar_id,
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
