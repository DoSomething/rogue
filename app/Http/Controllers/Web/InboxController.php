<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Services\CampaignService;
use Rogue\Http\Controllers\Controller;

class InboxController extends Controller
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
     * Show particular campaign inbox.
     *
     * @param  int $campaignId
     */
    public function show($campaignId)
    {
        // Get the campaign data
        $campaignData = $this->campaignService->find($campaignId);

        return view('pages.campaign_inbox')
            ->with('state', [
                'campaign' => $campaignData,
                'initial_posts' => 'pending',
            ]);
    }
}
