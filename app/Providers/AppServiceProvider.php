<?php

namespace Rogue\Providers;

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
