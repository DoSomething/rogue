<?php

namespace Rogue\Services;

use Rogue\Models\Reportback;

class ReportbackService
{
    protected $test;

    public function __construct()
    {
        $this->test = "test";
    }

    public function create($data) {
        $reportback = Reportback::create($data);

        return $reportback;
    }
}
