<?php

namespace Rogue\Http\Controllers;

use Rogue\Http\Requests\TagsRequest;

class TagsController extends Controller
{
    /**
     * Create a controller instance.
     *
     * @param PostContract $posts
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');
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
        // we don't even need a transformer - just need to know if the request was successful or not.

        //@TODO: do we want to know who is responsible for the adding/deleting the tag?
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
