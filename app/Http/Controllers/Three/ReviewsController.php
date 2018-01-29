<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Repositories\Three\PostRepository;
use Rogue\Http\Controllers\Api\ApiController;
use Illuminate\Auth\Access\AuthorizationException;
use Rogue\Http\Transformers\Three\PostTransformer;

class ReviewsController extends ApiController
{
    /**
     * The post repository instance.
     *
     * @var Rogue\Repositories\Three\PostRepository
     */
    protected $post;

    /**
     * @var \Rogue\Http\Transformers\Three\PostTransformer
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
        $this->post = $post;
        $this->transformer = new PostTransformer;

        $this->middleware('auth:api');
        $this->middleware('role:admin');
    }

    /**
     * Update a post(s)'s status when reviewed.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews(Request $request)
    {
        $request->validate([
            'post_id' => 'required:exists:posts',
            'status' => 'in:pending,accepted,rejected',
        ]);

        $review = $request->all();
        $post = Post::findOrFail($request['post_id']);
        $reviewedPost = $this->post->reviews($post, $request['status'], $request['comment']);
        $reviewedPostCode = $this->code($reviewedPost);

        info('post_reviewed', [
            'id' => $reviewedPost->id,
            'admin_northstar_id' => $reviewedPost->admin_northstar_id,
            'status' => $reviewedPost->status,
        ]);

        return $this->item($reviewedPost, $reviewedPostCode);
    }
}
