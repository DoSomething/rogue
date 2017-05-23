<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use Rogue\Repositories\PostRepository;
use Rogue\Http\Requests\ReviewsApiRequest;
use Rogue\Http\Transformers\PostTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReviewsController extends ApiController
{
    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\PostRepository
     */
    protected $post;

    /**
     * @var \Rogue\Http\Transformers\PostTransformer
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract $posts
     * @return void
     */
    public function __construct(PostRepository $post)
    {
        $this->middleware('api');

        $this->post = $post;
        $this->transformer = new PostTransformer;
    }

    /**
     * Update a post(s)'s status when reviewed.
     *
     * @param Rogue\Http\Requests\ReviewsApiRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews(ReviewsApiRequest $request)
    {
        $review = $request->all();
        $post = Post::where('id', $review['post_id'])->first();
        $review['signup_id'] = $post->signup_id;
        $review['northstar_id'] = $post->northstar_id;
        $review['old_status'] = $post->status;

        // Append admin's ID to the request for the "reviews" service.
        $review['admin_northstar_id'] = $review['admin_northstar_id'];
        $reviewedPost = $this->post->reviews($review);
        $reviewedPostCode = $this->code($reviewedPost);

        if (isset($reviewedPost)) {
            return $this->item($reviewedPost, $reviewedPostCode);
        } else {
            throw (new ModelNotFoundException)->setModel('Post');
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
