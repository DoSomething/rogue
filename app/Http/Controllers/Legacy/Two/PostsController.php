<?php

namespace Rogue\Http\Controllers\Legacy\Two;

use Rogue\Services\Legacy\Two\PostService;
use Rogue\Http\Controllers\Traits\PostRequests;
use Rogue\Repositories\Legacy\Two\SignupRepository;
use Rogue\Http\Transformers\Legacy\Two\PostTransformer;

class PostsController extends ApiController
{
    use PostRequests;

    /**
     * The post service instance.
     *
     * @var \Rogue\Services\Legacy\Two\PostService
     */
    protected $posts;

    /**
     * The signup repository instance.
     *
     * @var \Rogue\Repositories\Legacy\Two\SignupRepository
     */
    protected $signups;

    /**
     * @var Rogue\Http\Transformers\Legacy\Two\PostTransformer;
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
