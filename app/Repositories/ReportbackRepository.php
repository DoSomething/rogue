<?php

namespace Rogue\Repositories;

use Rogue\Models\Reportback;

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
        $reportback = Reportback::create($data);

        return $reportback;
    }
}
