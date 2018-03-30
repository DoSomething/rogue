<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Models\Post;
use Rogue\Services\PostService;
use Rogue\Http\Requests\ReviewsRequest;
use Rogue\Http\Transformers\Legacy\Two\PostTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Rogue\Http\Controllers\Controller;

class ReviewsController extends Controller
{
    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\PostRepository
     */
    protected $post;

    /**
     * @var \Rogue\Http\Transformers\Legacy\Two\PostTransformer
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract $posts
     * @return void
     */
    public function __construct(PostService $post)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->post = $post;
        $this->transformer = new PostTransformer;
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
        $review = $request->all();
        $post = Post::where('id', $review['post_id'])->first();
        $review['signup_id'] = $post->signup_id;
        $review['northstar_id'] = $post->northstar_id;
        $review['old_status'] = $post->status;

        // Append admin's ID to the request for the "reviews" service.
        $review['admin_northstar_id'] = auth()->user()->northstar_id;
        $reviewedPost = $this->post->review($review);
        $reviewedPostCode = $this->code($reviewedPost);
        $meta = [];

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
