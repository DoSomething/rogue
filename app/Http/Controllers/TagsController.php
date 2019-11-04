<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Repositories\PostRepository;
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
        $this->middleware('role:admin,staff');
        $this->middleware('scopes:write');
        $this->middleware('scopes:activity');
    }

    /**
     * Updates a post's tags when added or deleted.
     *
     * @param  Post $post
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Post $post, Request $request)
    {
        $request->validate([
            'tag_name' => 'required|string',
        ]);

        // If a tag slug is sent in, change to the tag name.
        // @TODO: This controller/model should really deal in slugs...
        $tag = $request->tag_name;
        if (str_contains($tag, '-')) {
            $tag = ucwords(str_replace('-', ' ', $tag));
        }

        // If the post already has the tag, remove it. Otherwise, add the tag to the post.
        if ($post->tagNames()->contains($tag)) {
            $updatedPost = $this->post->untag($post, $tag);
        } else {
            $updatedPost = $this->post->tag($post, $tag);
        }

        return $this->item($updatedPost);
    }
}
