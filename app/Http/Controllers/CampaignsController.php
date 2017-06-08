<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Signup;
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
        $campaigns = $this->campaignService->appendStatusCountsToCampaigns($campaigns);

        $causes = $this->campaignService->groupByCause($campaigns);

        return view('pages.campaign_overview')
            ->with('state', $causes);
    }

    /**
     * Show particular campaign inbox.
     */
    public function showInbox($campaignId)
    {
        $signups = Signup::campaign([$campaignId])->has('pending')->with('pending')->get();

        // For each post, get and include the user
        // @TODO - we should rethink this logic. Making a request to northstar
        // for each post might be heavy. Ideally we could grab/attach users in bulk when
        // we grab the signup.
        $signups->each(function ($item) {
            $item->posts->each(function ($item) {
                $user = $this->registrar->find($item->northstar_id);
                $item->user = $user->toArray();
            });
        });

        // Get the campaign data
        $campaign_data = $this->campaignService->find($campaignId);

        return view('pages.campaign_inbox')
            ->with('state', [
                'signups' => $signups,
                'campaign' => $campaign_data,
            ]);
    }

    public function showCampaign($id) {
        dd('show campaign');
    }
}
