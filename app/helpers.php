<?php

use Rogue\Models\Post;
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
        ->encode('data-url');

    return $editedImage;
}

/**
 * Query for total reactions for a post.
 *
 * @param int $postableId
 * @param int $postableType
 * @return int total count
 */
function getTotalPostReactions($postableId, $postableType)
{
    // return Reaction::where(['postable_id' => $postableId, 'postable_type' => $postableType])->count();

    // return Post::withCount('reactions')->get();

    return Post::withCount(['reactions' => function ($query) {
        $query->where(['postable_id' => $postableId, 'postable_type' => $postableType]);
    }])->get();
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
