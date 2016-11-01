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
 * Given an array of key/value pairs, create a new array with only the
 * keys given.
 *
 * @param  associative array  $data
 * @param  array $desiredKeys
 *
 * @return array
 */
function extract_values_by_keys($array, $desiredKeys)
{
    $desiredValues = array_intersect_key($array, array_flip($desiredKeys));

    return (count($array) > 0) ? $desiredValues : null;
}
