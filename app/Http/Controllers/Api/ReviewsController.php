<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use Rogue\Services\PostService;
use Rogue\Http\Requests\ReviewsRequest;
use Rogue\Http\Transformers\PostTransformer;

class ReviewsController extends ApiController
{
    /**
     * The post service instance.
     *
     * @var Rogue\Services\PostService
     */
    protected $post;

    /**
     * @var \Rogue\Http\Transformers\PostTransformer
     */
    protected $postTransformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract $posts
     * @return void
     */
    public function __construct(PostService $post)
    {
        $this->middleware('api');

        $this->post = $post;

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
        dd('hi');
        $reviewedPost = $this->post->reviews($request->all());
        $reviewedPostCode = $this->code($reviewedPost);

        $meta = [];

        if (isset($reviewedPost)) {
            return $this->item($reviewedPost, $reviewedPostCode, $meta, $this->postTransformer);
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
