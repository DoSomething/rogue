<?php

namespace Rogue\Services;

use Rogue\Services\Registrar;

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

    public function exportSignups($signups)
    {
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

        return $final_results;
    }
}