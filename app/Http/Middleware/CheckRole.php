<?php

namespace Rogue\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use DoSomething\Gateway\Server\Token;
use Illuminate\Auth\Access\AuthorizationException;
use DoSomething\Gateway\Server\Middleware\RequireRole;

class CheckRole extends RequireRole
{
    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     * @param Guard $auth
     */
    public function __construct(Guard $auth, Token $token)
    {
        $this->auth = $auth;

        parent::__construct($token);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string[] $roles
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // If using the 'api' guard, use Gateway's role middleware.
        if (auth()->getDefaultDriver() === 'api') {
            return parent::handle($request, $next, ...$roles);
        }

        // Otherwise, check the local user.
        if ($this->auth->guest() || ! $this->auth->user()->hasRole($roles)) {
            throw new AuthorizationException('You don\'t have the correct role to do that!');
        }

        return $next($request);
    }
}
