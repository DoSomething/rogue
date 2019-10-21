<?php

namespace Rogue\Providers;

use Closure;
use Hashids\Hashids;
use Rogue\Auth\CustomGate;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

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

        $this->app->singleton(Hashids::class, function ($app) {
            return new Hashids(config('app.key'), 10);
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

        // Add the 'joinSub' query builder method from Laravel 5.6+. <https://git.io/JeRBw>
        QueryBuilder::macro('joinSub', function ($query, $as, $first, $operator = null, $second = null, $type = 'inner', $where = false) {
            [$query, $bindings] = $this->createSub($query);

            $expression = '('.$query.') as '.$this->grammar->wrapTable($as);

            $this->addBinding($bindings, 'join');

            return $this->join(new Expression($expression), $first, $operator, $second, $type, $where);
        });

        // Add the 'leftJoinSub' query builder method from Laravel 5.6+. <https://git.io/JeRBr>
        QueryBuilder::macro('leftJoinSub', function ($query, $as, $first, $operator = null, $second = null) {
            return $this->joinSub($query, $as, $first, $operator, $second, 'left');
        });

        // Add the 'createSub' query builder method from Laravel 5.6+. <https://git.io/JeRB6>
        QueryBuilder::macro('createSub', function ($query) {
            if ($query instanceof Closure) {
                $callback = $query;
                $callback($query = $this->forSubQuery());
            }

            return $this->parseSub($query);
        });

        // Add the 'parseSub' query builder method from Laravel 5.6+. <https://git.io/JeRBP>
        QueryBuilder::macro('parseSub', function ($query) {
            if ($query instanceof self || $query instanceof EloquentBuilder) {
                return [$query->toSql(), $query->getBindings()];
            } elseif (is_string($query)) {
                return [$query, []];
            } else {
                throw new InvalidArgumentException(
                    'A subquery must be a query builder instance, a Closure, or a string.'
                );
            }
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
