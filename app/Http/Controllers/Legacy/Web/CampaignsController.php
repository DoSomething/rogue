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
        $this->middleware('auth', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('role:admin,staff', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Show overview of campaigns and their IDs.
     */
    public function index()
    {
        $campaigns = Campaign::all();

        // Format start and end dates how we want them to be viewed.
        foreach ($campaigns as $campaign) {
            $campaign = $this->formatStartEndDates($campaign, '');
        }

        return view('pages.campaigns_index')->with('campaigns', $campaigns);
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
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        // Change dates to YYYY-MM-DD format so it will save in the database.
        $campaignDetails = $this->formatDatesForDatabase($request->all());

        $campaign = Campaign::create($campaignDetails);

        // Log that a campaign was created.
        info('campaign_created', ['id' => $campaign->id]);

        return redirect()->route('campaign_id.show', $campaign->id);
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
            'start_date' => 'date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        // Change dates to YYYY-MM-DD format so it will save in the database.
        $campaignDetails = $this->formatDatesForDatabase($request->all());

        $campaign->update($campaignDetails);

        // Log that a campaign was updated.
        info('campaign_updated', ['id' => $campaign->id]);

        return redirect()->route('campaign_id.show', $campaign);
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

    /**
     * Create a new campaign.
     */
    public function create()
    {
        return view('pages.campaigns_create');
    }

    /**
     * Show a specific campaign page.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     */
    public function show(Campaign $campaign)
    {
        // Format start and end dates how we want them to be viewed.
        $campaign = $this->formatStartEndDates($campaign, 'There is no end date for this campaign.');

        return view('pages.campaigns_show')->with('campaign', $campaign);
    }

    /**
     * Edit a specific campaign page.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     */
    public function edit(Campaign $campaign)
    {
        // Format start and end dates how we want them to be viewed.
        $campaign = $this->formatStartEndDates($campaign, null);

        return view('pages.campaigns_edit')->with('campaign', $campaign);
    }

    /**
     * Helper function to format start and end dates for frontend.
     *
     * @param $campaign
     * @param $end_date_placeholder
     */
    public function formatStartEndDates($campaign, $end_date_placeholder)
    {
        $campaign->start_date = date("m/d/Y", strtotime($campaign->start_date));
        $campaign->end_date = $campaign->end_date ? date("m/d/Y", strtotime($campaign->end_date)) : $end_date_placeholder;

        return $campaign;
    }

    /** Helper function to format dates in YYYY-MM-DD format so it will save in the database.
     * @param Request $request
     */
    public function formatDatesForDatabase($request)
    {
        $formattedCampaign = [
            'internal_title' => $request['internal_title'],
            'start_date' => Carbon::parse($request['start_date']),
            'end_date' => $request['end_date'] ? Carbon::parse($request['end_date']) : null,
        ];

        return $formattedCampaign;
    }
}
