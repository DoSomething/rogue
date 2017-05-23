<?php

namespace Rogue\Http\Controllers;

use Rogue\Http\Requests\TagsRequest;
use Rogue\Repositories\PostRepository;
use Rogue\Http\Transformers\PostTransformer;

class TagsController extends Controller
{
    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\PostRepository
     */
    protected $post;

    /**
     * @var \Rogue\Http\Transformers\PostTransformer
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param PostContract $posts
     * @return void
     */
    public function __construct(PostRepository $post)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->post = $post;
        $this->transformer = new PostTransformer;
    }

    /**
     * Add or soft delete a tag to a post when reviewed.
     *
     * @param Rogue\Http\Requests\TagsRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TagsRequest $request)
    {
        $post = $this->post->find($request->input('post_id'));
        $tagged = $this->post->tag($post, $request->input('tag_name'));

        // @TODO: $post isn't showing the updated tags right now.

        // @TODO: We should use a transformer anywhere we send data to client.
        // return $this->item($post, $tagged ? 201 : 204);
        return response()->json($post, $tagged ? 201 : 204);
        // return response()->json(['tagged' => $tagged ? 'Tagged' : 'Untagged']);
        // return response()->json(['tag_name' => $request->input('tag_name')]);

    }
}
