<?php

namespace Rogue\Services;

use Rogue\Repositories\ReportbackRepository;
use Rogue\Services\Phoenix\Phoenix;

class ReportbackService
{
    protected $reportbackRepository;
    protected $phoenix;

    public function __construct(ReportbackRepository $reportbackRepository, Phoenix $phoenix)
    {
        $this->reportbackRepository = $reportbackRepository;
        $this->phoenix = $phoenix;
    }

    /*
     * Handles all the logic around creating a reportback.
     *
     * @param array $data
     * @return \Rogue\Models\Reportback $reportback.
     */
    public function create($data)
    {
        $reportback = $this->reportbackRepository->create($data);

        return $reportback;
    }
}
