<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\PostService;
use Rogue\Http\Requests\PostRequest;
use Rogue\Repositories\SignupRepository;
use Rogue\Http\Transformers\PostTransformer;
use Rogue\Http\Transformers\PhotoTransformer;

class PostsController extends ApiController
{
    /**
     * The photo service instance.
     *
     * @var Rogue\Services\PostService
     */
    protected $posts;

    /**
     * The signup repository instance.
     *
     * @var Rogue\Repositories\SignupRepository
     */
    protected $signups;

    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

    /**
     * @var \Rogue\Http\Transformers\PhotoTransformer
     */
    protected $photoTransformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(PostService $posts, SignupRepository $signups)
    {
        $this->middleware('api');

        $this->posts = $posts;
        $this->signups = $signups;

        // Now we have one PostTransformer to handle returning a Post to the API request.
        $this->transformer = new PostTransformer;
        $this->photoTransformer = new PhotoTransformer;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // @TODO - Validate post request.
    public function store(PostRequest $request)
    {
        $transactionId = incrementTransactionId($request);

        // @TODO - Remove. This is temporary. Just hardcoding some params in the request that the client would normally pass. But we assume everything is a photo post from a user at the moment.
        $request['submission_type'] = 'user';

        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id'], $request['campaign_run_id']);

        $updating = ! is_null($signup);

        // @TODO - should we eventually throw an error if a signup doesn't exist before a post is created? I create one here because we haven't implemented sending signups to rogue yet, so it will have to create a signup record for all posts.
        if (! $updating) {
            $signup = $this->signups->create($request->all());

            // Send the data to the PostService class which will handle determining
            // which type of post we are dealing with and which repostitory to use to actually create the post.
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
     * Update a post(s)'s status when reviewed.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews(Request $request)
    {
        $reviewedPosts = $this->posts->reviews($request->all());

        if (empty($reviewedPosts)) {
            $code = 404;
        } else {
            $code = 201;
        }

        $meta = [];

        // @TODO: we'll need to change the transformer here depending on what type of post.
        return $this->collection($reviewedPosts, $code, $meta, $this->photoTransformer);
    }
}
