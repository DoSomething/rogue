<?php

namespace Rogue\Http\Controllers\Api;

use Illuminate\Http\Request;
use Rogue\Models\Event;
use Rogue\Models\Signup;
use Rogue\Repositories\PhotoRepository;
use Rogue\Repositories\SignupRepository;

use Rogue\Http\Requests;

class PostsController extends ApiController
{

    /**
     * The photo repository instance.
     */
    protected $photos;

    /**
     * The signup repository instance.
     */
    protected $signups;

    /**
     * Create a controller instance.
     *
     * @param  PhotoRepository  $photos
     * @return void
     */
    public function __construct(PhotoRepository $photos, SignupRepository $signups)
    {
        $this->photos = $photos;
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

        // @TODO - should we eventually throw an error if a signup doesn't exist before a post is created?
        if (is_null($signup)) {
            $signup = $this->signups->create($request->all());
        }

        switch ($request['event_type']) {
            case 'post_photo':
                $this->photos->create($signup->id, $request->all());

                break;
            case 'video':
                // send to the video repository
                break;
            case 'text':
                // send to the text repository
                break;
            default:
                // Maybe default to the event repository
                break;
        }
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
