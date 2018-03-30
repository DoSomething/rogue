<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Repositories\Legacy\Two\PostRepository;
use Rogue\Http\Transformers\Legacy\Two\PostTransformer;
use Rogue\Http\Controllers\Traits\TagsRequests;
use Rogue\Http\Controllers\Controller;

class TagsController extends Controller
{
    use TagsRequests;
    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\Legacy\Two\PostRepository
     */
    protected $post;

    /**
     * @var \Rogue\Http\Transformers\Legacy\Two\PostTransformer
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
