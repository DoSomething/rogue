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

    public function create($data) {
        $reportback = $this->reportbackRepository->create($data);

        return $reportback;
    }
}
