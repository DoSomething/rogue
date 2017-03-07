<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use Rogue\Services\PostService;
use Rogue\Http\Requests\ReviewsRequest;
use Rogue\Http\Transformers\PostTransformer;

class ReviewsController extends ApiController
{
    /**
     * The photo service instance.
     *
     * @var Rogue\Services\PostService
     */
    protected $posts;

    /**
     * @var \Rogue\Http\Transformers\PostTransformer
     */
    protected $postTransformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(PostService $posts)
    {
        $this->middleware('api');

        $this->posts = $posts;

        $this->postTransformer = new PostTransformer;
    }

    /**
     * Update a post(s)'s status when reviewed.
     *
     * @param Rogue\Http\Requests\ReviewsRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews(ReviewsRequest $request)
    {
        $reviewedPhoto = $this->posts->reviews($request->all());
        $reviewedPhotoCode = $this->code($reviewedPhoto);

        $meta = [];

        if (isset($reviewedPhoto)) {
            return $this->item($reviewedPhoto, $reviewedPhotoCode, $meta, $this->postTransformer);
        } else {
            return 404;
        }
    }

    /**
     * Determine status code.
     *
     * @param array $reviewed
     *
     * @return int $code
     */
    public function code($reviewed)
    {
        if (empty($reviewed)) {
            return 404;
        } else {
            return 201;
        }
    }
}
