<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends ApiController
{

    /**
     * Create a controller instance.
     *
     */
    public function __construct()
    {
        // $this->transformer = $transformer;

        $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('role:admin,staff', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('scopes:write', ['only' => ['store', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'internal_title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Check to see if the campaign exists before creating one.
        $campaign = Campaign::where([
            'internal_title' => $request['internal_title'],
        ])->first();

        $code = $signup ? 200 : 201;

        // If there is no campaign with that title, create one.
        if (! $campaign) {
            $campaign = new Campaign;

            $campaign->internal_title = $request['internal_title'];
            $campaign->start_date = $request['start_date'];
            $campaign->end_date = $request['end_date'];
            $campaign->save();
        }

        return $this->item($campaign, $code);
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
