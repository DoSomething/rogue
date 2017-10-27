<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Services\PostService;
use Rogue\Repositories\SignupRepository;
use Rogue\Http\Transformers\PostTransformer;
use Rogue\Http\Controllers\Traits\PostRequests;
use Rogue\Http\Controllers\Api\ApiController;

class PostsController extends ApiController
{
    use PostRequests;

    /**
     * The post service instance.
     *
     * @var \Rogue\Services\PostService
     */
    protected $posts;

    /**
     * The signup repository instance.
     *
     * @var \Rogue\Repositories\SignupRepository
     */
    protected $signups;

    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(PostService $posts, SignupRepository $signups)
    {
        $this->posts = $posts;
        $this->signups = $signups;

        $this->transformer = new PostTransformer;
    }
}
