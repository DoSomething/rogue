<?php

namespace Rogue\Http\Controllers\Legacy\Web;

use Rogue\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Rogue\Http\Controllers\Controller;
use Rogue\Http\Transformers\CampaignTransformer;

class CampaignIdsController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->rules = [
            'internal_title' => ['required', 'string'],
            'cause' => ['required', 'array', 'between:1,5'],
            'cause.*' => ['string', Rule::in(array_keys(Campaign::$causes))],
            'impact_doc' => ['required', 'url'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ];
    }

    /**
     * Show overview of campaigns and their IDs.
     */
    public function index()
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')->get()->toArray();

        return view('campaign-ids.index')->with('state', $campaigns);
    }

    /**
     * Create a new campaign.
     */
    public function create()
    {
        return view('campaign-ids.create')->with('causes', Campaign::$causes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $values = $this->validate($request, array_merge_recursive($this->rules, [
            'internal_title' => [Rule::unique('campaigns')],
        ]));

        $campaign = Campaign::create($values);

        // Log that a campaign was created.
        info('campaign_created', ['id' => $campaign->id]);

        return redirect()->route('campaign-ids.show', $campaign->id);
    }

    /**
     * Show a specific campaign page and its actions.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     */
    public function show(Campaign $campaign)
    {
        return view('campaign-ids.show')
            ->with('state', [
                'campaign' => (new CampaignTransformer)->transform($campaign),
            ]
        );
    }

    /**
     * Edit a specific campaign page.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     */
    public function edit(Campaign $campaign)
    {
        return view('campaign-ids.edit', [
            'campaign' => $campaign,
            'causes' => Campaign::$causes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Rogue\Models\Campaign  $campaign
     */
    public function update(Request $request, Campaign $campaign)
    {
        $values = $this->validate($request, array_merge_recursive($this->rules, [
            'internal_title' => [Rule::unique('campaigns')->ignore($campaign->id)],
        ]));

        $campaign->update($values);

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
