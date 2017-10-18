<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Signup;
use Rogue\Jobs\ExportSignups;

class ExportController extends Controller
{
    /**
     * Instantiate a new ExportController instance.
     *
     * @param Rogue\Services\Registrar $registrar
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Download the export of signup details for the specified campaign.
     *
     * @param int $campaignId
     */
    public function show($campaignId)
    {
        // Dispatch an Export job to the queue.
        dispatch((new ExportSignups($campaignId)));

        return redirect()->route('campaigns.show', ['campaign_id' => $campaignId])->with('status', 'Your email is in the works! An email with your export will be sent to you shortly.');
    }
}
