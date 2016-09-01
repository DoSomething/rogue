<?php

namespace Rogue\Repositories;

use Rogue\Models\Reportback;
use Rogue\Models\ReportbackLog;
use Rogue\Services\AWS;

class ReportbackRepository
{
    protected $AWS;

    public function __construct(AWS $aws)
    {
        $this->aws = $aws;
    }

    /**
     * Create a new reportback.
     *
     * @todo Handle errors better during creation.
     * @param  array $data
     * @return \Rogue\Models\Reportback|null
     */
    public function create($data)
    {
        $reportback = Reportback::create($data);

        if ($reportback) {
            $reportback = $this->addItem($reportback, $data);

            // Record transaction in log table.
            $this->log($reportback, $data, 'insert');

            return $reportback;
        }

        return null;
    }

    /**
     * Update an existing reportback.
     *
     * @param  \Rogue\Models\Reportback $reportback
     * @param  array $data
     *
     * @return \Rogue\Models\Reportback
     */
    public function update($reportback, $data)
    {
        if (isset($data['file'])) {
            $reportback = $this->addItem($reportback, $data);
        }

        $reportback->fill(array_only($data, ['quantity', 'why_participated', 'num_participants', 'flagged', 'flagged_reason', 'promoted', 'promoted_reason']));

        $reportback->save();

        $this->log($reportback, $data, 'update');

        return $reportback;
    }

    /**
     * Log a record in the reportback_logs table to track operations done on a reportback.
     *
     * @param  \Rogue\Models\Reportback $reportback
     * @param  array $data
     * @param  string $operation
     *
     * @return \Rogue\Models\Reportback
     */
    public function log($reportback, $data, $operation)
    {
        // Record transaction in log table.
        $log = new ReportbackLog;

        $logData = [
            'op' => $operation,
            'reportback_id' => $reportback->id,
            'files' => $reportback->items->implode('file_id', ','),
            'num_files' => $reportback->items->count(),
        ];

        $data = array_merge($data, $logData);

        $log->fill($data);
        $log->save();

        return $reportback;
    }

    /**
     * Add a new item to a reportback.
     *
     * @param  \Rogue\Models\Reportback $reportback
     * @param  array $data
     *
     * @return \Rogue\Models\Reportback
     */
    public function addItem($reportback, $data)
    {
        if (isset($data['file'])) {
            // @todo - this part right here might actually belong in the service class now that i think about it.
            $data['file_url'] = $this->aws->storeImage($data['file'], $data['file_id']);

            $reportback->items()->create(array_only($data, ['file_id', 'file_url', 'caption', 'status', 'reviewed', 'reviewer', 'review_source', 'source', 'remote_addr']));
        }

        return $reportback;
    }

    /*
     * Get a user's reportback based on their drupal id or their northstar id.
     *
     * @param string|int $campaignId
     * @param string|int $campaignRunId
     * @param string|int $userId
     * @param string $type
     *
     * @return \Rogue\Models\Reportback|null
     */
    public function getReportbackByUser($campaignId, $campaignRunId, $userId, $type = 'northstar_id')
    {
        if (! in_array($type, ['northstar_id', 'drupal_id'])) {
            throw new \Exception('Invalid user ID type provided.');
        }

        $parameters = [
            $type => $userId,
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
        ];

        try {
            return Reportback::where($parameters)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }
    }
}
