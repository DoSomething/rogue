<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Services\Three\PostService;
use Rogue\Http\Controllers\Api\ApiController;
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
    public function __construct(PostService $post)
    {
        $this->post = $post;
        $this->transformer = new PostTransformer;

        $this->middleware('auth:api');
        $this->middleware('role:admin');
        $this->middleware('scope:write');
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
            'post_id' => 'required',
            'status' => 'in:pending,accepted,rejected',
        ]);

        $post = Post::findOrFail($request['post_id']);
        $reviewedPost = $this->post->review($post, $request['status'], $request['comment']);

        return $this->item($reviewedPost, 201);
    }
}
