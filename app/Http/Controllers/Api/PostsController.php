<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\PostService;
use Rogue\Repositories\SignupRepository;
use Rogue\Http\Transformers\PostTransformer;

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
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(PostService $posts, SignupRepository $signups)
    {
        $this->posts = $posts;
        $this->signups = $signups;

        // Now we have one PostTransformer to handle returning a Post to the API request.
        $this->transformer = new PostTransformer;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // @TODO - Validate post request.
    public function store(Request $request)
    {
        $transactionId = incrementTransactionId($request);

        // @TODO - Remove. This is temporary. Just hardcoding some params in the request that the client would normally pass. But we assume everything is a photo post from a user at the moment.
        $request['event_type'] = 'post_photo';
        $request['submission_type'] = 'user';

        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id'], $request['campaign_run_id']);

        // @TODO - should we eventually throw an error if a signup doesn't exist before a post is created? I create one here because we haven't implemented sending signups to rogue yet, so it will have to create a signup record for all posts.
        if (is_null($signup)) {
            $signup = $this->signups->create($request->all());
        }

        // Send the data to the PostService class which will handle delgating
        // which type of post we are dealing with and which repostitory to use to // actually create the post.
        $post = $this->posts->create($request->all(), $signup->id, $transactionId);

        return $this->item($post);
    }
}
