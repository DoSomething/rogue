<?php

namespace Rogue\Http\Controllers;

use Rogue\Services\Registrar;
use Rogue\Services\CampaignService;

class CampaignsController extends Controller
{
    /**
     * Registrar instance
     *
     * @var Rogue\Services\Registrar
     */
    protected $registrar;

    /**
     * Phoenix instance
     *
     * @var Rogue\Services\CampaignService
     */
    protected $campaignService;

    /**
     * Constructor
     *
     * @param Rogue\Services\Registrar $registrar
     * @param Rogue\Services\CampaignService $campaignService
     */
    public function __construct(Registrar $registrar, CampaignService $campaignService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->registrar = $registrar;
        $this->campaignService = $campaignService;
    }

    /**
     * Show overview of campaigns.
     */
    public function index()
    {
        $ids = $this->campaignService->getCampaignIdsFromSignups();
        $campaigns = $this->campaignService->findAll($ids);

        $causes = $campaigns ? $this->campaignService->groupByCause($campaigns) : null;

        return view('pages.campaign_overview')
            ->with('state', $causes);
    }

    /**
     * Show particular campaign inbox.
     *
     * @param  int $campaignId
     */
    public function showInbox($campaignId)
    {
        // Get the campaign data
        $campaignData = $this->campaignService->find($campaignId);

        $env = get_client_environment_vars();

        return view('pages.campaign_inbox', [
            'env' => $env,
        ])->with('state', [
            'campaign' => $campaignData,
            'initial_posts' => 'pending',
        ]);
    }

    /**
     * Show particular campaign and it's posts.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showCampaign($id)
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
