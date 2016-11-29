<?php

namespace Rogue\Services;

use Rogue\Models\Reportback;
use Rogue\Repositories\ReportbackRepository;
use Rogue\Jobs\SendReportbackToPhoenix;

class ReportbackService
{
    /*
     * Reportback repository instance.
     *
     * @var \Rogue\Repositories\ReportbackRepository
     */
    protected $reportbackRepository;

    /**
     * Constructor
     *
     * @param \Rogue\Repositories\ReportbackRepository $reportbackRepository
     */
    public function __construct(ReportbackRepository $reportbackRepository)
    {
        $this->reportbackRepository = $reportbackRepository;
    }

    /*
     * Handles all the logic around creating a reportback.
     *
     * @param array $data
     * @param string $transactionId
     * @return \Rogue\Models\Reportback $reportback.
     */
    public function create($data, $transactionId)
    {
        $reportback = $this->reportbackRepository->create($data);

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        // POST reportback back to Phoenix.
        // If request fails, record in failed_jobs table.
        dispatch(new SendReportbackToPhoenix($reportback));

        return $reportback;
    }

    /*
     * Handles all the business logic around updating a reportback.
     *
     * @param \Rogue\Models\Reportback $reportback
     * @param array $data
     * @param string $transactionId
     *
     * @return \Rogue\Models\Reportback $reportback.
     */
    public function update($reportback, $data, $transactionId)
    {
        $reportback = $this->reportbackRepository->update($reportback, $data);

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        // POST reportback update back to Phoenix.
        // If request fails, record in failed_jobs table.
        dispatch(new SendReportbackToPhoenix($reportback, isset($data['file'])));

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

    /*
     * Handles all business logic around update a reportbackitem(s).
     *
     * @param array $data
     *
     * @return
     */
    public function updateReportbackItems($data)
    {
        $items = $this->reportbackRepository->updateReportbackItems($data);

        return $items;
    }
}
