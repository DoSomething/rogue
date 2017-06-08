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
     *
     * @param  int $campaignId
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
        $campaignData = $this->campaignService->find($campaignId);

        return view('pages.campaign_inbox')
            ->with('state', [
                'signups' => $signups,
                'campaign' => $campaignData,
            ]);
    }

    /**
     * Show particular campaign and it's posts.
     *
     * @param  int $id
     */
    public function showCampaign($id) {
        $signups = Signup::campaign([$id])->has('accepted')->with('accepted')->get();

        // @TODO EXTRACT AND FIGURE OUT HOW NOT TO HAVE TO DO THIS.
        $signups->each(function ($item) {
            $item->posts->each(function ($item) {
                $user = $this->registrar->find($item->northstar_id);
                $item->user = $user->toArray();
            });
        });

        $campaignData = $this->campaignService->find($id);

        return view('pages.campaign_single')
            ->with('state', [
                'signups' => $signups,
                'campaign' => $campaignData,
            ]);
    }
}
