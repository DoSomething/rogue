<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Services\PostService;
use Rogue\Http\Controllers\Legacy\Two\ApiController;
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
