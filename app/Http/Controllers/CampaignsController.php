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
        $campaigns = $this->campaignService->appendPendingCountsToCampaigns($campaigns);

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
        $signups = Signup::campaign([$campaignId])->has('pending')->with('pending')->get();

        // For each pending post, get and include the user
        // @TODO - we should rethink this logic. Making a request to northstar
        // for each post might be heavy. Ideally we could grab/attach users in bulk when
        // we grab the signup.
        $signups->each(function ($item) {
            $item->posts = $item->pending;

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
    public function showCampaign($id)
    {
        // Always load the page with just accepted posts
        $signups = Signup::campaign([$id])->has('posts')->with(['posts' => function ($query) {
            $query->where('status', '=', 'accepted');
        }])->orderBy('created_at', 'desc')->paginate(50);

        // @TODO EXTRACT AND FIGURE OUT HOW NOT TO HAVE TO DO THIS.
        $signups->each(function ($item) {
            $item->posts->each(function ($item) {
                $user = $this->registrar->find($item->northstar_id);

                if ($user) {
                    $item->user = $user->toArray();
                }
            });
        });

        $campaign = $this->campaignService->find($id);
        $totals = $this->campaignService->getPostTotals($campaign);

        return view('pages.campaign_single')
            ->with('state', [
                'signups' => $signups->items(),
                'campaign' => $campaign,
                'post_totals' => [
                    'accepted_count' => $totals->accepted_count,
                    'pending_count' => $totals->pending_count,
                    'rejected_count' => $totals->rejected_count,
                ],
                'next_page' => $signups->nextPageUrl(),
                'previous_page' => $signups->previousPageUrl(),
            ]);
    }
}
