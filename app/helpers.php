<?php

use Illuminate\Support\HtmlString;
use Intervention\Image\Facades\Image;

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
 * Crop and rotate an image based on given parameters.
 *
 * @param  mixed  $image
 * @return string $image base-64 encoded data URI
 */
function edit_image($image, $coords)
{
    $editedImage = (string) Image::make($image)
        // Intervention Image rotates images counter-clockwise, but we get values assuming clockwise rotation, so we negate it to rotate clockwise.
        ->rotate(-$coords['crop_rotate'])
        ->crop($coords['crop_width'], $coords['crop_height'], $coords['crop_x'], $coords['crop_y'])
        ->fit(400)
        ->encode('jpg', 75);

    return $editedImage;
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
