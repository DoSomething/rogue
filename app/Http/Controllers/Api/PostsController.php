<?php

namespace Rogue\Http\Controllers\Api;

use Illuminate\Http\Request;
use Rogue\Models\Event;
use Rogue\Models\Signup;
use Rogue\Services\PostService;
use Rogue\Repositories\SignupRepository;

use Rogue\Http\Requests;

class PostsController extends ApiController
{

    /**
     * The photo service instance.
     */
    protected $posts;

    /**
     * The signup repository instance.
     */
    protected $signups;

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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transactionId = incrementTransactionId($request);

        // @TODO - Remove. This is temporary. Just hardcoding some params in the request that the client would normally pass. But we assume everything is a photo post at the moment.
        $request['event_type'] = 'post_photo';
        $request['submission_type'] = 'user';


        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id'], $request['campaign_run_id']);

        // @TODO - should we eventually throw an error if a signup doesn't exist before a post is created? I create one here because we haven't implemented sending signups to rogue yet, so it will have to create a signup record for all posts.
        if (is_null($signup)) {
            $signup = $this->signups->create($request->all());
        }

        $this->posts->create($request->all(), $signup->id, $transactionId);

        dd('done');
    }
}
