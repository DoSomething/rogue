<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Models\Campaign;
use Rogue\Http\Controllers\Controller;

class InboxController extends Controller
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
     * Show particular campaign inbox.
     *
     * @param  int $campaignId
     */
    public function show($campaignId)
    {
        // Get the campaign data
        $campaignData = Campaign::findOrFail($campaignId);

        return view('pages.campaign_inbox')
            ->with('state', [
                'campaign' => $campaignData,
                'initial_posts' => 'pending',
            ]);
    }
}
