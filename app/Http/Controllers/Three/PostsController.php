<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Services\Three\PostService;
use Rogue\Repositories\Three\SignupRepository;
use Rogue\Http\Requests\Three\PostRequest;
use Rogue\Http\Transformers\Three\PostTransformer;
use Rogue\Http\Controllers\Api\ApiController;
use Rogue\Http\Controllers\Traits\FiltersRequests;

class PostsController extends ApiController
{
    use FiltersRequests;

    /**
     * The post service instance.
     *
     * @var \Rogue\Services\Three\PostService
     */
    protected $posts;

    /**
     * The signup repository instance.
     *
     * @var \Rogue\Repositories\Three\SignupRepository
     */
    protected $signups;

    /**
     * @var \Rogue\Http\Transformers\PostTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param PostService $posts
     * @param SignupRepository $signups
     * @param PostTransformer $transformer
     */
    public function __construct(PostService $posts, SignupRepository $signups, PostTransformer $transformer)
    {
        $this->posts = $posts;
        $this->signups = $signups;
        $this->transformer = $transformer;

        $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('role:admin', ['only' => ['store', 'update', 'destroy']]); // @TODO: Allow anyone to use this.
    }

    /**
     * Returns Posts, filtered by params, if provided.
     * GET /posts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Post::class)
            ->withCount('reactions')
            ->orderBy('created_at', 'desc');

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Post::$indexes);

        // If a user made the request, return whether or not they liked each post.
        if (auth()->check()) {
            $query = $query->with(['reactions' => function ($query) {
                $query->where('northstar_id', '=', auth()->id());
            }]);
        }

        // Only allow admins or staff to see un-approved posts from other users.
        $canSeeAllPosts = token()->exists() && in_array(token()->role, ['admin', 'staff']);
        if (! $canSeeAllPosts) {
            $query = $query->where(function ($query) {
                $query->where('status', 'accepted')
                    ->orWhere('northstar_id', auth()->id());
            });
        }

        // If tag param is passed, only return posts that have that tag.
        if (array_has($filters, 'tag')) {
            $query = $query->withTag($filters['tag']);
        }

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $transactionId = incrementTransactionId($request);

        $signup = $this->signups->get(auth()->id(), $request['campaign_id'], $request['campaign_run_id']);

        $updating = ! is_null($signup);

        // @TODO - should we eventually throw an error if a signup doesn't exist before a post is created? I create one here because we haven't implemented sending signups to rogue yet, so it will have to create a signup record for all posts.
        if (! $updating) {
            $signup = $this->signups->create($request->all());
            $post = $this->posts->create($request->all(), $signup->id, $transactionId);

            $code = 200;

            return $this->item($post);
        } else {
            $post = $this->posts->update($signup, $request->all(), $transactionId);

            $code = 201;

            if (isset($request['file'])) {
                return $this->item($post);
            } else {
                return $signup;
            }
        }
    }

    /**
     * Returns a specific post.
     * GET /posts/:id
     *
     * @param \Rogue\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post)
    {
        return $this->item($post);
    }

    /**
     * Updates a specific post.
     * PATCH /posts/:id
     *
     * @param PostRequest $request
     * @param \Rogue\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->only('status', 'caption'));

        return $this->item($post);
    }

    /**
     * Delete a post.
     * DELETE /posts/:id
     *
     * @param \Rogue\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return $this->respond('Post deleted.', 200);
    }
}
