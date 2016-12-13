<?php

namespace Rogue\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->call([$this, 'registerPost']);
        // $this->app->bind('Rogue\Repositories\PostContract', 'Rogue\Repositories\PhotoRepository');

    }

    public function registerPost(Request $request)
    {
        // @TODO - remove. Hardcoded here cause everythign is a photo at the moment
        if (is_null($request->event_type)) {
            $request->event_type = 'post_photo';
        }

        $this->app->bind('Rogue\Repositories\PostContract', function ($app) use ($request) {
            // I use $app->make so processor classes dependancies gets resolved and injected.
            // if ($request->eventype == 'typeone') return $app->make(TypeOneProcessor::class);
            // if ($request->type == 'typetwo') return $app->make(TypeTwoProcessor::class);
            switch ($request->event_type) {
                case 'post_photo':
                    return $app->make('Rogue\Repositories\PhotoRepository');

                    break;
                case 'video':
                    // send to the video repository
                    break;
                case 'text':
                    // send to the text repository
                    break;
                default:
                    // Maybe default to the event repository
                    break;
            }

        });
    }
}
