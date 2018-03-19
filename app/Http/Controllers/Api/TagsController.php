<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Repositories\PostRepository;
use Rogue\Http\Transformers\PostTransformer;
use Rogue\Http\Controllers\Traits\TagsRequests;

class TagsController extends ApiController
{
    use TagsRequests;
    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\PostRepository
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
    }
}
