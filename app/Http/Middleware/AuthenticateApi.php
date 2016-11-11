<?php

namespace Rogue\Http\Middleware;

use Closure;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() === 'POST' || $request->method() === 'PUT') {
            if ($request->header('X-DS-Rogue-API-Key') !== env('ROGUE_API_KEY')) {
                throw new UnauthorizedAccessException;
            }
        }

        return $next($request);
    }
}
