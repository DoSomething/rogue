<?php

namespace Rogue\Providers;

use Rogue\Models\Post;
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
        Signup::created(function ($signup) {
            $signup->events()->create([
                'content' => $signup->toJson(),
            ]);
        });

        Post::created(function ($post) {
            $post->events()->create([
                'content' => $post->toJson(),
            ]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
