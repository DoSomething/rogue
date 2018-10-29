<?php

namespace Rogue\Http\Controllers\Legacy\Web;

use Rogue\Models\Campaign;
use Illuminate\Http\Request;
use Rogue\Http\Controllers\Controller;

class CampaignsController extends Controller
{
    /**
     * Create a controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('role:admin,staff', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('scopes:write', ['only' => ['store', 'update', 'destroy']]);
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

            // Log that a campaign was created.
            info('campaign_created', ['id' => $campaign->id]);
        }

        // @TODO: return redirect()->route('campaigns.show', $campaign->id);
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

        // Log that a campaign was updated.
        info('campaign_updated', ['id' => $campaign->id]);

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

        // Log that a campaign was deleted.
        info('campaign_deleted', ['id' => $campaign->id]);

        // @TODO: redirect to campaign deleted page
    }
}
