<?php

namespace Rogue\Services;

use Rogue\Repositories\ReportbackRepository;

class ReportbackService
{
    protected $reportbackRepository;

    public function __construct(ReportbackRepository $reportbackRepository)
    {
        $this->reportbackRepository = $reportbackRepository;
    }

    /*
     * Handles all steps needed in creating a reportback.
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
