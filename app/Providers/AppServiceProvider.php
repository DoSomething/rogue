<?php

namespace Rogue\Providers;

use DoSomething\Gateway\Blink;
use Illuminate\Support\ServiceProvider;

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
        // @TODO: This should be registered in Gateway's service provider!
        $this->app->singleton(Blink::class, function () {
            return new Blink(config('services.blink'));
        });
    }
}
