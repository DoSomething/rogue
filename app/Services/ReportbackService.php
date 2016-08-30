<?php

namespace Rogue\Services;

use Rogue\Models\Reportback;
use Rogue\Repositories\ReportbackRepository;
use Rogue\Services\Phoenix\Phoenix;

class ReportbackService
{
    /*
     * Instance of \Rogue\Repositories\ReportbackRepository
     */
    protected $reportbackRepository;

    /*
     * Instance of \Rogue\Services\Phoenix\Phoenix
     */
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

        // POST reportback back to phoenix.
        $body = [
            'uid' => $reportback->drupal_id,
            'nid' => $reportback->campaign_id,
            'quantity' => $reportback->quantity,
            'why_participated' => $reportback->why_participated,
            'file_url' => $reportback['items'][0]['file_url'],
            // these are required if file_url is not provided. do we want to send this back?
            // 'file'
            // 'filename'
            'caption' => $reportback['items'][0]['caption'],
            'source' => $reportback['items'][0]['source']
        ];

        $this->phoenix->postReportback($reportback->campaign_id, $body);

        return $reportback;
    }

    /*
     * Handles all the business logic around updating a reportback.
     *
     * @param \Rogue\Models\Reportback $reportback
     * @param array $data
     *
     * @return \Rogue\Models\Reportback $reportback.
     */
    public function update($reportback, $data)
    {
        $reportback = $this->reportbackRepository->update($reportback, $data);

        return $reportback;
    }

    /*
     * Check if a reportback already exists for a given user,
     * on a specific campaign, and campaign run.
     *
     * @param string|int $campaignId
     * @param string|int $campaignRunId
     * @param string|int $userId
     * @param string $type
     *
     * @return \Rogue\Models\Reportback|null
     */
    public function getReportback($campaignId, $campaignRunId, $userId, $type)
    {
        $reportback = $this->reportbackRepository->getReportbackByUser($campaignId, $campaignRunId, $userId, $type);

        return $reportback ? $reportback : null;
    }
}
