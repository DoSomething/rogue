<?php

namespace Rogue\Http\Controllers;

class CampaignsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');
    }

    /**
     * Show overview of campaigns.
     */
    public function index()
    {
        $staffPicks = collect([
            ['name' => 'Campaign 1', 'approved' => 53, 'pending' => 32, 'rejected' => 34, 'deleted' => 3],
            ['name' => 'Campaign 2', 'approved' => 54, 'pending' => 33, 'rejected' => 35, 'deleted' => 4],
            ['name' => 'Campaign 3', 'approved' => 55, 'pending' => 34, 'rejected' => 36, 'deleted' => 5],
        ]);

        $environment = collect([
            ['name' => 'Campaign 4', 'approved' => 53, 'pending' => 32, 'rejected' => 34, 'deleted' => 3],
            ['name' => 'Campaign 5', 'approved' => 54, 'pending' => 33, 'rejected' => 35, 'deleted' => 4],
            ['name' => 'Campaign 6', 'approved' => 55, 'pending' => 34, 'rejected' => 36, 'deleted' => 5],
        ]);

        $bullying = collect([
            ['name' => 'Campaign 7', 'approved' => 53, 'pending' => 32, 'rejected' => 34, 'deleted' => 3],
            ['name' => 'Campaign 8', 'approved' => 54, 'pending' => 33, 'rejected' => 35, 'deleted' => 4],
            ['name' => 'Campaign 9', 'approved' => 55, 'pending' => 34, 'rejected' => 36, 'deleted' => 5],
        ]);

        return view('pages.campaign_overview')
            ->with('state', [
                'Staff Picks' => $staffPicks,
                'Environment' => $environment,
                'Bullying' => $bullying,
            ]);
    }
}
