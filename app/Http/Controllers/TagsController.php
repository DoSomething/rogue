<?php

namespace Rogue\Http\Controllers;

use Rogue\Http\Controllers\Traits\TagsRequests;
use Rogue\Http\Requests\TagsRequest;
use Rogue\Repositories\PostRepository;
use Rogue\Http\Transformers\PostTransformer;

class TagsController extends Controller
{
    use TagsRequests;
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
}
