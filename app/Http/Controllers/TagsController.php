<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Repositories\PostRepository;
use Rogue\Http\Controllers\Legacy\Two\ApiController;
use Rogue\Http\Transformers\PostTransformer;

class TagsController extends ApiController
{
    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\PostRepository
     */
    protected $post;

    /**
     * @var Rogue\Http\Transformers\PostTransformer;
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
        $this->middleware('scopes:write');
        $this->middleware('scopes:activity');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'tag_name' => 'required|string',
        ]);

        $post = $this->post->find($post->id);

        $taggedPost = $this->post->tag($post, $request->tag_name);

        return $this->item($taggedPost);
    }

    /**
     * Remove a tag from a post.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        $request->validate([
            'tag_name' => 'required|string',
        ]);

        $post = $this->post->find($post->id);

        $untaggedPost = $this->post->untag($post, $request->tag_name);

        return $this->item($untaggedPost);
    }
}
