<?php

namespace Rogue\Services;

use League\Csv\Writer;
use SplTempFileObject;
use Rogue\Models\Signup;
use Illuminate\Support\Facades\Storage;

class ExportService
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
     * Prepare the export of signup details for the specified campaign.
     *
     * @param object $signups
     * @param int $campaignId
     */
    public function exportSignups($campaignId)
    {
        // return $campaignId;
        $writer = Writer::createFromFileObject(new SplTempFileObject());

        // Set up column headers
        $headers = ['Campaign ID', 'Campaign Run ID', 'Northstar ID', 'First Name', 'Email', 'Mobile', 'Age'];

        $writer->insertOne($headers);

        $signups = Signup::whereNull('details')->where('campaign_id', $campaignId)->cursor();

        foreach ($signups as $signup) {
            $northstarUser = $this->registrar->find($signup->northstar_id);

            $nextRow = [
                'campaign_id' => $signup->campaign_id,
                'campaign_run_id' => $signup->campaign_run_id,
                'northstar_id' => $signup->northstar_id,
                'first_name' => $northstarUser->first_name,
                'email' => $northstarUser->email,
                'mobile' => $northstarUser->mobile,
                'age' => getAgeFromBirthdate($northstarUser->birthdate),
            ];

            $writer->insertOne($nextRow);
        }

        Storage::put('export_'.$campaignId.'.csv', $writer->__toString());
    }
}
