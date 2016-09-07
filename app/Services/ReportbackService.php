<?php

namespace Rogue\Services;

use Rogue\Models\Reportback;
use Rogue\Repositories\ReportbackRepository;
use Rogue\Services\Phoenix\Phoenix;
use Rogue\Models\FailedLog;

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
            'file_url' => $reportback->items()->first()->file_url,
            'caption' => $reportback->items()->first()->caption,
            'source' => $reportback->items()->first()->source,
        ];

        $phoenixResponse = $this->phoenix->postReportback($reportback->campaign_id, $body);

        // If POST to Phoenix fails, record in failed_logs table.
        if ($phoenixResponse === false) {
            $failedLog = new FailedLog;

            $logData = [
                'op' => 'POST to Phoenix',
                'drupal_id' => $body['uid'],
                'nid' => $body['nid'],
                'quantity' => $body['quantity'],
                'why_participated' => $body['why_participated'],
                'file_url' => $body['file_url'],
                'caption' => $body['caption'],
                'source' => $body['source'],
            ];

            $failedLog->fill($logData);
            $failedLog->save();
        }

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
