<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Repositories\Three\PostRepository;
use Rogue\Http\Controllers\Api\ApiController;
use Rogue\Http\Transformers\Three\PostTransformer;

class TagsController extends ApiController
{
    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\Three\PostRepository
     */
    protected $post;

    /**
     * @var \League\Fractal\TransformerAbstract;
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

        // Check to see if the post already has this tag.
        // If so, no need to add again.
        if (! $post->tagNames()->contains($request->tag_name)) {
            $post = $this->post->tag($post, $request->tag_name);
        }

        return $this->item($post);
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

        // Check to see if the post already has this tag.
        // If so, delete the tag.
        if ($post->tagNames()->contains($request->tag_name)) {
            $post = $this->post->untag($post, $request->tag_name);
        }

        return $this->item($post);
    }
}
