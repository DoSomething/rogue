<?php

namespace Rogue\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use League\Glide\Filesystem\FileNotFoundException as GlideNotFoundException;

class Handler extends ExceptionHandler
{
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
     * @param  \Exception  $exceptionxception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        // If Intervention can't parse a file (corrupted or wrong type), return 422.
        // @TODO: Handle this with a validation rule on our v3 routes.
        if ($exception instanceof \Intervention\Image\Exception\NotReadableException) {
            abort(422, 'Invalid image provided.');
        }

        // Re-cast specific exceptions or uniquely render them:
        if ($exception instanceof GlideNotFoundException) {
            $exception = new NotFoundHttpException('That image could not be found.');
        } elseif ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException('That resource could not be found.');
        }

        return parent::render($request, $exception);
    }

    /**
     * Get the default context variables for logging exceptions.
     *
     * @return array
     */
    protected function context()
    {
        // We handle adding context in AppServiceProvider, and specifically
        // want to disable Laravel's default behavior of appending email here.
        return [];
    }
}
