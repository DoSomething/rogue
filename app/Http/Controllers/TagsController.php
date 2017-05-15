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
}
