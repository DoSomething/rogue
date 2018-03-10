<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Signup;
use Rogue\Jobs\ExportSignups;
use Rogue\Services\Registrar;
use Rogue\Services\CampaignService;

class ExportController extends Controller
{
    /**
     * Campaign Service instance
     *
     * @var Rogue\Services\CampaignService
     */
    protected $campaigns;

    /**
     * Registrar instance
     *
     * @var Rogue\Services\Registrar
     */
    protected $registrar;

    /**
     * Instantiate a new ExportController instance.
     *
     * @param Rogue\Services\Registrar $registrar
     */
    public function __construct(CampaignService $campaigns, Registrar $registrar)
    {
        $this->middleware('auth');
        $this->campaigns = $campaigns;
        $this->registrar = $registrar;
    }

    /**
     * Download the export of signup details for the specified campaign.
     *
     * @param int $campaignId
     */
    public function show($campaignId)
    {
        // Get the full campaign object.
        $campaign = $this->campaigns->find($campaignId);

        // Get the email of the authenticated user that made the request.
        $user = $this->registrar->find(auth()->user()->northstar_id);

        $adminEmail = $user->email;

        // Dispatch an Export job to the queue.
        dispatch((new ExportSignups($campaign, $adminEmail)));

        return redirect()->route('campaigns.show', ['campaign_id' => $campaignId])->with('status', 'Your email is in the works! An email with your export will be sent to you shortly.');
    }
}
