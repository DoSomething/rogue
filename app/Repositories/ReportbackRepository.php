<?php

namespace Rogue\Repositories;

use Rogue\Models\Reportback;
use Rogue\Models\ReportbackLog;

class ReportbackRepository
{
    /**
     * Create a new reportback
     *
     * @param  array $data
     * @return \Gladiator\Models\Message
     */
    public function create($data)
    {
        // Store reportback
        $reportback = Reportback::create($data);


        // @TODO: Store reportback item.

        // Record transaction in log table.
        $log = new ReportbackLog;

        $logData = [
            'op' => 'insert',
            'reportback_id' => $reportback->id,
        ];

        $data = array_merge($data, $logData);

        $log->fill($data);
        $log->save();

        return $reportback;
    }
}
