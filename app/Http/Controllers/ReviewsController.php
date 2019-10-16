<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Managers\PostManager;
use Rogue\Http\Transformers\PostTransformer;

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
     * @param  PostManager $post
     * @return void
     */
    public function __construct(PostManager $post)
    {
        $this->post = $post;
        $this->transformer = new PostTransformer;

        $this->middleware('auth:api');
        $this->middleware('role:admin,staff');
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
    public function reviews(Request $request, Post $post)
    {
        $request->validate([
            'status' => 'in:pending,accepted,rejected',
        ]);

        $reviewedPost = $this->post->review($post, $request['status'], $request['comment']);

        return $this->item($reviewedPost, 201);
    }
}
