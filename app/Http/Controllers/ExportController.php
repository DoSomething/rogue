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

        // Compile the data
        $final_results = $this->export->exportSignups($signups);

        // Format as csv
        $output = '';
        foreach ($final_results as $row) {
            $output .= implode(',', array_values($row)) . "\n";
        }

        // Build and return the file
        $filename = 'export_' . $campaignId . '.csv';
        $headers = [
          'Content-Type'        => 'text/csv',
          'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response($output, 200, $headers);
    }
}
