<?php

namespace Rogue\Http\Controllers\Legacy\Web;

use Carbon\Carbon;
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
        $this->middleware('auth');
        $this->middleware('role:admin,staff', ['only' => ['index', 'show']]);
        $this->middleware('role:admin', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    /**
     * Show overview of campaigns and their IDs.
     */
    public function index()
    {
        $campaigns = Campaign::paginate(25);

        return view('campaign-ids.index')->with('campaigns', $campaigns);
    }

    /**
     * Create a new campaign.
     */
    public function create()
    {
        return view('campaign-ids.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'internal_title' => 'required|string|unique:campaigns',
            'cause' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $campaign = Campaign::create($request->all());

        // Log that a campaign was created.
        info('campaign_created', ['id' => $campaign->id]);

        return redirect()->route('campaign-ids.show', $campaign->id);
    }

    /**
     * Show a specific campaign page.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     */
    public function show(Campaign $campaign)
    {
        return view('campaign-ids.show')->with('campaign', $campaign);
    }

    /**
     * Edit a specific campaign page.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     */
    public function edit(Campaign $campaign)
    {
        return view('campaign-ids.edit')->with('campaign', $campaign);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Rogue\Models\Campaign  $campaign
     */
    public function update(Request $request, Campaign $campaign)
    {
        $this->validate($request, [
            'internal_title' => 'string',
            'cause' => 'string',
            'start_date' => 'date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $campaign->update($request->all());

        // Log that a campaign was updated.
        info('campaign_updated', ['id' => $campaign->id]);

        return redirect()->route('campaign-ids.show', $campaign);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        // Log that a campaign was deleted.
        info('campaign_deleted', ['id' => $campaign->id]);

        // @TODO: redirect to campaign deleted page
    }
}
