<?php

namespace Rogue\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
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
