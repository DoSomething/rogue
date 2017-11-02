<?php

namespace Rogue\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateApi extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Only protected POST, PUT, PATCH, and DELETE routes
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $next($request);
        }

        // Allow using the `X-DS-Rogue-API-Key` header to override auth.
        if ($request->hasHeader('X-DS-Rogue-API-Key')) {
            if ($request->header('X-DS-Rogue-API-Key') !== config('app.api_key')) {
                throw new AuthenticationException;
            }

            return $next($request);
        }

        // Otherwise, pass on to Laravel's default Authenticate
        // middleware with the 'api' guard specified.
        return parent::handle($request, $next, 'api');
    }
}
