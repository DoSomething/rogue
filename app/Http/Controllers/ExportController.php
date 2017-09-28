<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\Registrar;

class ExportController extends Controller
{
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
    public function __construct(Registrar $registrar)
    {
        $this->registrar = $registrar;
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
		$final_results = [];

		foreach ($signups as $signup) {
	        $northstar_user = $this->registrar->find($signup->northstar_id);

			$next_row = [
				'campaign_id' => $signup->campaign_id,
				'campaign_run_id' => $signup->campaign_run_id,
				'northstar_id' => $signup->northstar_id,
				'first_name' => $northstar_user->first_name,
				'email' => $northstar_user->email,
				'mobile' => $northstar_user->mobile,
				'age' => getAgeFromBirthdate($northstar_user->birthdate),
			];

			array_push($final_results, $next_row);
		}

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
