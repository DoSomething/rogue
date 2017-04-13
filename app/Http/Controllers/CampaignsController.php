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
        $campaignsWithCounts = $campaigns->map(function($campaign, $key) {
            if ($campaign) {

                $campaign['accepted_count'] = 0;
                $campaign['pending_count'] = 0;
                $campaign['rejected_count'] = 0;

                $signups = Signup::has('posts')->where('campaign_id', '=', $campaign['id'])
                    ->withCount(['accepted', 'pending', 'rejected'])->get();

                $signups->each(function($signup) use ($campaign) {
                    $campaign['accepted_count'] += $signup['accepted_count'];
                    $campaign['pending_count'] += $signup['pending_count'];
                    $campaign['rejected_count'] += $signup['rejected_count'];
                });
            }

            return $campaign;
        });

        $causes = $this->campaignService->groupByCause($campaignsWithCounts);
        dd($causes);
        return view('pages.campaign_overview')
            ->with('state', $causes);
    }

    /**
     * Show particular campaign inbox.
     */
    public function show($campaign_run_id)
    {
        // Pull in all signups for the given run that have pending posts, and include their pending posts
        $signups = Signup::whereHas('posts', function ($query) {
            $query->where('status', 'pending');
        })->where('campaign_run_id', $campaign_run_id)->with('posts')->get();

        // For each post, get and include the user
        $signups->each(function ($item) {
            $item->posts->each(function ($item) {
                $user = $this->registrar->find($item->northstar_id);
                $item->user = $user->toArray();
            });
        });

        return view('pages.campaign_inbox')
            ->with('state', [
                'signups' => $signups,
            ]);
    }
}
