<?php

namespace Rogue\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\Access\AuthorizationException;

class CheckRole
{
    /**
     * The Guard implementation.
     */
    protected $auth;

    /**
     * Create a new filter instance.
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if ($this->auth->guest() || ! $this->auth->user()->hasRole($roles)) {
            throw new AuthorizationException('You don\'t have the correct role to do that!');
        }

        return $next($request);
    }
}
