<?php

namespace Rogue\Http\Controllers\Api;

use Illuminate\Http\Request;
use Rogue\Models\Event;
use Rogue\Models\Signup;
use Rogue\Repositories\PostContract;
use Rogue\Repositories\SignupRepository;

use Rogue\Http\Requests;

class PostsController extends ApiController
{

    /**
     * The photo repository instance.
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
    public function __construct(PostContract $posts, SignupRepository $signups)
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
        // $transactionId = incrementTransactionId($request);

        // TEMP - just hardcoding some params in the request that the client would normally set on its end in this new world.
        $request['event_type'] = 'post_photo';
        $request['submission_type'] = 'user';


        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id'], $request['campaign_run_id']);

        // @TODO - should we eventually throw an error if a signup doesn't exist before a post is created? I create one here because we haven't implemented sending signups to rogue yet, so it will have to create a signup record for all posts.
        if (is_null($signup)) {
            $signup = $this->signups->create($request->all());
        }

        $this->posts->create($signup->id, $request->all());

        dd('done');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
