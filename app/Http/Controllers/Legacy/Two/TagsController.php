<?php

namespace Rogue\Http\Controllers\Legacy\Two;

use Rogue\Http\Controllers\ApiController;
use Rogue\Http\Controllers\Traits\TagsRequests;
use Rogue\Repositories\Legacy\Two\PostRepository;
use Rogue\Http\Transformers\Legacy\Two\PostTransformer;

class TagsController extends ApiController
{
    use TagsRequests;
    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\Legacy\Two\PostRepository
     */
    protected $post;

    /**
     * @var Rogue\Http\Transformers\Legacy\Two\PostTransformer;
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
    }
}
