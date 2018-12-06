<?php

namespace Rogue\Services;

use League\Csv\Writer;
use SplTempFileObject;
use Rogue\Models\Signup;

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
     *
     * @return string CSV data
     */
    public function exportSignups($campaignId)
    {
        // return $campaignId;
        $writer = Writer::createFromFileObject(new SplTempFileObject());

        // Set up column headers
        $headers = ['Campaign ID', 'Campaign Run ID', 'Northstar ID', 'First Name', 'Last Name', 'Email', 'Mobile', 'Zip Code', 'DOB', 'Age'];

        $writer->insertOne($headers);

        $signups = Signup::whereNull('details')->where([
            ['campaign_id', '=', $campaignId],
            ['source', '=', 'phoenix-next'],
        ])->cursor();

        foreach ($signups as $signup) {
            $northstarUser = $this->registrar->find($signup->northstar_id);

            $nextRow = [
                'campaign_id' => $signup->campaign_id,
                'northstar_id' => $signup->northstar_id,
                'first_name' => $northstarUser->first_name ?? 'N/A',
                'last_name' => $northstarUser->last_name ?? 'N/A',
                'email' => $northstarUser->email ?? 'N/A',
                'mobile' => $northstarUser->mobile ?? 'N/A',
                'zip_code' => $northstarUser->addr_zip ?? 'N/A',
                'birthdate' => $northstarUser->birthdate ?? 'N/A',
                'age' => isset($northstarUser->birthdate) ? getAgeFromBirthdate($northstarUser->birthdate) : 'N/A',
            ];

            $writer->insertOne($nextRow);
        }

        return (string) $writer;
    }
}
