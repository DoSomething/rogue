<?php

namespace Rogue\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Rogue\Auth\CustomGate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->singleton(CustomGate::class, function ($app) {
        //     return new Gate($app, function () use ($app) {
        //         return call_user_func($app['auth']->userResolver());
        //     });
        // });

        $this->app->singleton(GateContract::class, function ($app) {
            return new CustomGate($app, function () use ($app) {
                return call_user_func($app['auth']->userResolver());
            });
        });

        // Add a custom validator for Mongo ObjectIDs.
        Validator::extend('objectid', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-f\d]{24}$/i', $value);
        }, 'The :attribute must be a valid ObjectID.');

        // Attach the user & request ID to context for all log messages.
        Log::getMonolog()->pushProcessor(function ($record) {
            $record['extra']['user_id'] = auth()->id();
            $record['extra']['client_id'] = token()->client();
            $record['extra']['request_id'] = request()->header('X-Request-Id');

            return $record;
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
