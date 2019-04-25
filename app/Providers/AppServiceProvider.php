<?php

namespace Rogue\Providers;

use Rogue\Auth\CustomGate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
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
        $this->app->singleton(GateContract::class, function ($app) {
            return new CustomGate($app, function () use ($app) {
                return call_user_func($app['auth']->userResolver());
            });
        });

        // Register global view composer.
        View::composer('*', function ($view) {
            $view->with('auth', [
                'id' => auth()->id(),
                'token' => auth()->user() ? auth()->user()->access_token : null,
                'role' => auth()->user() ? auth()->user()->role : 'user',
            ]);
        });

        // Add a custom validator for Mongo ObjectIDs.
        Validator::extend('objectid', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-f\d]{24}$/i', $value);
        }, 'The :attribute must be a valid ObjectID.');

        Validator::extend('iso3166', function ($attribute, $value, $parameters, $validator) {
            $isoCodes = new \Sokil\IsoCodes\IsoCodesFactory();
            $subDivisions = $isoCodes->getSubdivisions();

            return ! is_null($subDivisions->getByCode($value));
        }, 'The :attribute must be a valid ISO-3166-2 region code.');

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
