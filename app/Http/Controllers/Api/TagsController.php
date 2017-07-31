<?php

namespace Rogue\Http\Controllers\Api;

class TagsController extends ApiController
{
    /**
     * Create a controller instance.
     *
     * @param  PostContract $posts
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api');
    }

    public function store(TagRequest $request)
    {
        dd('Here is what we got:', $request);
    }
}
