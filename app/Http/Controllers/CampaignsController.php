<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Signup;
use Rogue\Services\Phoenix;
use Rogue\Services\Registrar;
use Illuminate\Support\Facades\DB;
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
     * @var Rogue\Services\Phoenix
     */
    protected $phoenix;

    /**
     * Phoenix instance
     *
     * @var Rogue\Services\CampaignService
     */
    protected $campaignService;

    /**
     * Constructor
     *
     * @param \Rogue\Services\Phoenix $phoenix
     */
    public function __construct(Phoenix $phoenix)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->phoenix = $phoenix;
        $this->registrar = new Registrar();
        $this->campaignService = new CampaignService;
    }

    /**
     * Show overview of campaigns.
     */
    public function index()
    {
        // Get campaigns ids for all signups in rogue.
        $campaigns = DB::table('signups')->select('campaign_id')->groupBy('campaign_id')->get();
        $ids = collect($campaigns)->pluck('campaign_id')->toArray();

        $campaigns = $this->campaignService->findAll($ids);
        $campaigns = $this->campaignService->groupByCause($campaigns);

        return view('pages.campaign_overview')
            ->with('state', $campaigns);
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
