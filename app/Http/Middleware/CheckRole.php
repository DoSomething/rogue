<?php

namespace Rogue\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;


class CheckRole
{
    /**
     * The Guard implementation.
     *
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
    public function handle($request, Closure $next)
    {
        $roles = array_slice(func_get_args(), 2);

        if ($this->auth->guest() || !$this->auth->user()->hasRole($roles)) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return view('auth.unauthorized');
            }
        }

        return $next($request);
    }
}
