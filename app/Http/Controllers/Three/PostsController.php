<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Services\PostService;
use Rogue\Repositories\SignupRepository;
use Rogue\Http\Transformers\PostTransformer;
use Rogue\Http\Controllers\Api\ApiController;
use Rogue\Http\Controllers\Traits\PostRequests;

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
     * @var \Rogue\Http\Transformers\PostTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(PostService $posts, SignupRepository $signups, PostTransformer $transformer)
    {
        $this->posts = $posts;
        $this->signups = $signups;

        $this->transformer = $transformer;
    }

    /**
     * Returns a specific post.
     * GET /posts/:id
     *
     * @param Request $request
     * @param \Rogue\Models\Post $post
     * @return Illuminate\Http\Response
     */
    public function show(Request $request, Post $post)
    {
        return $this->item($post, 200, [], $this->transformer);
    }
}
