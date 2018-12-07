<?php

namespace Rogue\Http\Controllers\Legacy\Web;

use Rogue\Services\CampaignService;
use Rogue\Http\Controllers\Controller;

class CampaignsController extends Controller
{
    /**
     * Campaign Service instance
     *
     * @var Rogue\Services\CampaignService
     */
    protected $campaignService;

    /**
     * Constructor
     *
     * @param Rogue\Services\CampaignService $campaignService
     */
    public function __construct(CampaignService $campaignService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->campaignService = $campaignService;
    }

    /**
     * Show overview of campaigns.
     */
    public function index()
    {
        $ids = $this->campaignService->getCampaignIdsFromSignups();
        $campaigns = $this->campaignService->findAll($ids);
        $campaigns = $this->campaignService->appendPendingCountsToCampaigns($campaigns);

        $causes = $campaigns ? $this->campaignService->groupByCause($campaigns) : null;

        return view('pages.campaign_overview')
            ->with('state', $causes);
    }

    /**
     * Show particular campaign and it's posts.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = $this->campaignService->find($id);
        $totals = $this->campaignService->getPostTotals($campaign);

        return view('pages.campaign_single')
            ->with('state', [
                'campaign' => $campaign,
                'initial_posts' => 'accepted',
                'post_totals' => [
                    'accepted_count' => $totals->accepted_count,
                    'pending_count' => $totals->pending_count,
                    'rejected_count' => $totals->rejected_count,
                ],
            ]);
    }
}
