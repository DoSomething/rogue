<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Managers\PostManager;
use Rogue\Http\Transformers\PostTransformer;
use Rogue\Http\Controllers\Legacy\Two\ApiController;

class ReviewsController extends ApiController
{
    /**
     * The post manager instance.
     *
     * @var Rogue\Managers\PostManager
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
    public function __construct(PostManager $post)
    {
        $this->post = $post;
        $this->transformer = new PostTransformer;

        $this->middleware('auth:api');
        $this->middleware('role:admin');
        $this->middleware('role:staff');
        $this->middleware('scopes:write');
        $this->middleware('scopes:activity');
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
