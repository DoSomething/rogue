<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Repositories\PostRepository;
use Rogue\Http\Transformers\PostTransformer;
use Rogue\Http\Controllers\Legacy\Two\ApiController;

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
        $this->middleware('role:admin,staff');
        $this->middleware('scopes:write');
        $this->middleware('scopes:activity');
    }

    /**
     * Updates a post's tags when added or deleted.
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

        // If the post already has the tag, remove it. Otherwise, add the tag to the post.
        if ($post->tagNames()->contains($request->tag_name)) {
            $updatedPost = $this->post->untag($post, $request->tag_name);
        } else {
            $updatedPost = $this->post->tag($post, $request->tag_name);
        }

        return $this->item($updatedPost);
    }
}
