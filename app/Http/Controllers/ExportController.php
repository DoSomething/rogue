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
        // Compile the data and trigger the CSV download
        return $this->export->exportSignups($campaignId);
    }
}
