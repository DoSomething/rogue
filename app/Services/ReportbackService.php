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

        $body = [
            'quantity' => $reportback->quantity,
            'uid' => $reportback->drupal_id,
            'file_url' => $reportback->file,
            'why_participated' => $reportback->why_participated,
            'caption' => $reportback->caption,
            'source' => $reportback->source
        ];

        $response = $phoenix->postReportback($reportback->campaign_id, $body);

        return $response;
    }
}
