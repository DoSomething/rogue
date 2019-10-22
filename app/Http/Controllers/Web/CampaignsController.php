<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Models\Campaign;
use Rogue\Http\Controllers\Controller;

class CampaignsController extends Controller
{
    /**
     * Constructor
     */
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
        $campaigns = Campaign::withPendingPostCount()->get();

        $sortedCampaigns = $campaigns->sortByDesc('pending_count')
            ->groupBy(function ($campaign) {
                $isActive = $campaign->isOpen();
                $hasPendingPosts = $campaign->pending_count > 0;

                return $isActive && $hasPendingPosts ? 'pending' : 'etc';
            })->toArray();

        return view('pages.campaign_overview')
            ->with('state', $sortedCampaigns);
    }

    /**
     * Show particular campaign and its posts.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = Campaign::withPendingPostCount()->findOrFail($id);

        return view('pages.campaign_single')
            ->with('state', [
                'campaign' => $campaign,
                'initial_posts' => 'accepted',
                'post_totals' => [
                    'pending_count' => $campaign->pending_count,
                ],
            ]);
    }
}
