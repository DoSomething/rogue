<?php

use Illuminate\Support\HtmlString;

/**
 * Create a script tag to set a global variable.
 *
 * @param $json
 * @param string $store
 * @return HtmlString
 */
function scriptify($json = [], $store = 'STATE')
{
    return new HtmlString('<script type="text/javascript">window.'.$store.' = '.json_encode($json).'</script>');
}

/**
 * Helper function to increment transaction id.
 *
 * @param \Illuminate\Http\Request $request
 * @return $newTransactionId
 */
function incrementTransactionId($request)
{
    $transactionId = $request->header('X-Request-ID');

    if ($transactionId) {
        $transactionIdParts = explode('-', $transactionId);
        $incrementedStep = $transactionIdParts[1] + 1;
        $newTransactionId = $transactionIdParts[0] . '-' . $incrementedStep;

        return $newTransactionId;
    }

    return null;
}

/**
 * Check if the current route has any middleware attached.
 *
 * @param  null|string  $middleware
 * @return bool
 */
function has_middleware($middleware = null)
{
    $currentRoute = app('router')->getCurrentRoute();
    if (! $currentRoute) {
        return false;
    }
    if ($middleware) {
        return in_array($middleware, $currentRoute->middleware());
    }

    return $currentRoute->middleware() ? true : false;
}
