<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Signup;
use Rogue\Services\ExportService;

class ExportController extends Controller
{
    /**
     * ExportService instance
     *
     * @var Rogue\Services\ExportService
     */
    protected $export;

    /**
     * Instantiate a new ExportController instance.
     *
     * @param Rogue\Services\Registrar $registrar
     */
    public function __construct(ExportService $export)
    {
        $this->middleware('auth');
        $this->export = $export;
    }

    /**
     * Download the export of signup details for the specified campaign.
     *
     * @param int $campaignId
     */
    public function show($campaignId)
    {
        // Run the query
        $signups = Signup::whereNotNull('details')->where('campaign_id', $campaignId)->get();

        // Compile the data and trigger the CSV download
        return $this->export->exportSignups($signups, $campaignId);
    }
}
