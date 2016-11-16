<?php

namespace Rogue\Http\Middleware;

use Closure;

class LogReceivedRequest
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
        // Increment the incoming transaction id's step.
        $newTransactionId = incrementTransactionId($request);

        // Log that the request has been received.
        logger()->info('Request received. Transaction ID: ' . $newTransactionId);

        return $next($request);
    }
}
