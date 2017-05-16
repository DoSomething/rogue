<?php

namespace Rogue\Http\Controllers;

use Rogue\Http\Requests\TagsRequest;
use Rogue\Repositories\PostRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagsController extends Controller
{

    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\PostRepository
     */
    protected $post;

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
        $tagData = $request->all();
        $tagData['admin_northstar_id'] = auth()->user()->northstar_id;
        $taggedPost = $this->post->tag($tagData);

        if (isset($taggedPost)) {
            return 201;
        } else {
            throw (new ModelNotFoundException)->setModel('Post');
        }
    }
}
