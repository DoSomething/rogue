<?php

namespace Rogue\Http\Middleware;

use Closure;

class AddCacheKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        $params = $request->route()->parameters();
        $post = $params['post'];

        $response = $next($request);
        $response->headers->set('Surrogate-Key', $type.'-'.$post->id);

        return $response;
    }
}
