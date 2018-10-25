<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Campaign;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\CampaignTransformer;

class CampaignsController extends ApiController
{
    /**
     * @var Rogue\Http\Transformers\SignupTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     */
    public function __construct()
    {
        $this->transformer = new CampaignTransformer;

        // $this->middleware('auth');
        // $this->middleware('role:admin,staff', ['only' => ['store', 'update', 'destroy']]);
        // $this->middleware('scopes:write', ['only' => ['store', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Campaign::class);

        return $this->paginatedCollection($query, $request);
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

        // If there is no campaign with that title, create one.
        if (! $campaign) {
            $campaign = new Campaign;

            $campaign->internal_title = $request['internal_title'];
            $campaign->start_date = $request['start_date'];
            $campaign->end_date = $request['end_date'];
            $campaign->save();
        }

        // @TODO: return redirect()->route('campaigns.show', $campaign->id);
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
     * @param  \Rogue\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        $campaign->update($request->all());

        // @TODO: return redirect()->route('campaigns.show', $campaign->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        // @TODO: redirect to campaign deleted page
    }
}
