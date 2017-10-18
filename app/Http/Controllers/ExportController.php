<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Signup;
use Rogue\Jobs\ExportSignups;
use Rogue\Services\CampaignService;

class ExportController extends Controller
{
    protected $campaigns;

    /**
     * Instantiate a new ExportController instance.
     *
     * @param Rogue\Services\Registrar $registrar
     */
    public function __construct(CampaignService $campaigns)
    {
        $this->middleware('auth');
        $this->campaigns = $campaigns;
    }

    /**
     * Download the export of signup details for the specified campaign.
     *
     * @param int $campaignId
     */
    public function show($campaignId)
    {
        $campaign = $this->campaigns->find($campaignId);

        // Dispatch an Export job to the queue.
        dispatch((new ExportSignups($campaign)));

        return redirect()->route('campaigns.show', ['campaign_id' => $campaignId])->with('status', 'Your email is in the works! An email with your export will be sent to you shortly.');
    }
}
