<?php

namespace Rogue\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Rogue\Http\Middleware\TrustProxies::class,
        \Rogue\Http\Middleware\TrimStrings::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Rogue\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Rogue\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \DoSomething\Gateway\Laravel\Middleware\RefreshTokenMiddleware::class,
        ],

        'api' => [
            'guard:api',
            'bindings',
            // 'throttle:60,1',
            'cors' => \Rogue\Http\Middleware\Cors::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guard' => \DoSomething\Gateway\Server\Middleware\SetGuard::class,
        'scopes' => \DoSomething\Gateway\Server\Middleware\RequireScope::class,
        'legacy-auth' => \Rogue\Http\Middleware\AuthenticateWithApiKey::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \Rogue\Http\Middleware\RedirectIfAuthenticated::class,
        'role' => \Rogue\Http\Middleware\CheckRole::class,
    ];
}
