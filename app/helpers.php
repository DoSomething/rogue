<?php

use Intervention\Image\Facades\Image;

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
        ->encode('data-url');

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
    $transactionIdParts = explode('-', $transactionId);
    $incrementedStep = $transactionIdParts[1] + 1;
    $newTransactionId = $transactionIdParts[0] . '-' . $incrementedStep;

    return $newTransactionId;
}
