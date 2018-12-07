<?php

namespace Rogue\Http\Controllers\Legacy\Two;

use Rogue\Managers\Legacy\Two\PostManager;
use Rogue\Http\Requests\Legacy\Two\PostRequest;
use Rogue\Http\Controllers\Traits\FiltersRequests;
use Rogue\Repositories\Legacy\Two\SignupRepository;
use Rogue\Http\Transformers\Legacy\Two\PostTransformer;

class PostsController extends ApiController
{
    use FiltersRequests;

    /**
     * The post manager instance.
     *
     * @var \Rogue\Managers\Legacy\Two\PostManager
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
    public function __construct(PostManager $posts, SignupRepository $signups)
    {
        $this->posts = $posts;
        $this->signups = $signups;

        $this->transformer = new PostTransformer;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id']);

        $updating = ! is_null($signup);

        // @TODO - should we eventually throw an error if a signup doesn't exist before a post is created? I create one here because we haven't implemented sending signups to rogue yet, so it will have to create a signup record for all posts.
        if (! $updating) {
            $signup = $this->signups->create($request->all());

            $post = $this->posts->create($request->all(), $signup->id);

            $code = 200;

            return $this->item($post);
        } else {
            $post = $this->posts->update($signup, $request->all());

            $code = 201;

            if (isset($request['file'])) {
                return $this->item($post);
            } else {
                return $signup;
            }
        }
    }

    /**
     * Returns Posts, filtered by params, if provided.
     * GET /posts
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Post::class)->with('signup')->withCount('reactions')->orderBy('created_at', 'desc');

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Post::$indexes);

        // If user param is passed, return whether or not the user has liked the particular post.
        if ($request->query('as_user')) {
            $userId = $request->query('as_user');
            $query = $query->with(['reactions' => function ($query) use ($userId) {
                $query->where('northstar_id', '=', $userId);
            }]);
        }

        // If tag param is passed, only return posts that have that tag.
        if (array_has($filters, 'tag')) {
            $query = $query->withTag($filters['tag']);
        }

        return $this->paginatedCollection($query, $request);
    }
}
