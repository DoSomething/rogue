<?php

namespace Rogue\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use League\Glide\Filesystem\FileNotFoundException as GlideNotFoundException;

class Handler extends ExceptionHandler
{
    const PRODUCTION_ERROR_MESSAGE = 'Looks like something went wrong. We\'ve noted the problem and will try to get it fixed!';

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        // Re-cast specific exceptions or uniquely render them:
        if ($e instanceof GlideNotFoundException) {
            $e = new NotFoundHttpException('That image could not be found.');
        } elseif ($e instanceof AuthorizationException) {
            return parent::render($request, $e);
        } elseif ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        } elseif ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException('That resource could not be found.');
        }
        // If request has 'Accepts: application/json' header or we're on a route that
        // is in the `api` middleware group, render the exception as JSON object.
        if ($request->ajax() || $request->wantsJson() || has_middleware('api')) {
            return $this->buildJsonResponse($e);
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into an redirect or JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unauthenticated($request, AuthenticationException $e)
    {
        if ($request->expectsJson()) {
            return $this->buildJsonResponse(new HttpException(401, 'Unauthorized.'));
        }

        return redirect()->guest('login');
    }

    /**
     * Build a JSON error response for API clients.
     *
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function buildJsonResponse(Exception $e)
    {
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        }

        $code = $e instanceof HttpException ? $e->getStatusCode() : 500;

        $shouldHideErrorDetails = $code == 500 && ! config('app.debug');
        $response = [
            'error' => [
                'code' => $code,
                'message' => $shouldHideErrorDetails ? self::PRODUCTION_ERROR_MESSAGE : $e->getMessage(),
            ],
        ];
        // Show more information if we're in debug mode
        if (config('app.debug')) {
            $response['debug'] = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }

        return response()->json($response, $code);
    }
}
