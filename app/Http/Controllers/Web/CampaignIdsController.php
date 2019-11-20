<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Types\Cause;
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
            'cause.*' => ['string', Rule::in(Cause::all())],
            'impact_doc' => ['required', 'url'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        return redirect('campaigns/');
    }

    /**
     * Create a new campaign.
     */
    public function create()
    {
        return view('campaign-ids.create')->with('causes', Cause::labels());
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
            'causes' => Cause::labels(),
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
