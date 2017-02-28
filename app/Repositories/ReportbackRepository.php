<?php

namespace Rogue\Repositories;

use Rogue\Services\AWS;
use Rogue\Models\Reportback;
use Rogue\Models\ReportbackLog;
use Rogue\Models\ReportbackItem;
use Rogue\Models\Event;

class ReportbackRepository
{
    /**
     * AWS service class instance.
     *
     * @var \Rogue\Services\AWS
     */
    protected $AWS;

    /**
     * Array of properties needed for cropping and rotating.
     *
     * @var array
     */
    protected $cropProperties = ['crop_x', 'crop_y', 'crop_width', 'crop_height', 'crop_rotate'];

    /**
     * Constructor
     */
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
        } elseif (array_key_exists('file', $data)) {
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
            'files' => $reportback->items->implode('file_url', ','),
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
            $data['file_url'] = $this->aws->storeImage($data['file'], $data['campaign_id']);

            $cropValues = array_only($data, $this->cropProperties);

            if (count($cropValues) > 0) {
                $editedImage = edit_image($data['file'], $cropValues);

                $data['edited_file_url'] = $this->aws->storeImage($editedImage, 'edited_' . $data['campaign_id']);
            }

            $reportback->items()->create(array_only($data, ['file_id', 'file_url', 'edited_file_url', 'caption', 'status', 'reviewed', 'reviewer', 'review_source', 'source', 'remote_addr']));
        } elseif (array_key_exists('file', $data)) {
            $data['file_url'] = 'default';

            $reportback->items()->create(array_only($data, ['file_id', 'file_url', 'edited_file_url', 'caption', 'status', 'reviewed', 'reviewer', 'review_source', 'source', 'remote_addr']));
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

        return Reportback::where($parameters)->first();
    }

    /**
     * Updates an individual reportbackitem or many reportback items.
     *
     * @param array $data
     *
     * @return
     */
    public function updateReportbackItems($data)
    {
        $reportbackItems = [];

        foreach ($data as $reportbackItem) {
            if (isset($reportbackItem['rogue_reportback_item_id']) && ! empty($reportbackItem['rogue_reportback_item_id'])) {
                $rbItem = ReportbackItem::where(['id' => $reportbackItem['rogue_reportback_item_id']])->first();
                $rb = Reportback::where(['id' => $rbItem->reportback_id])->first();

                if ($reportbackItem['status'] && ! empty($reportbackItem['status'])) {
                    // @TODO: update to add more details in the event e.g. admin who reviewed, admin's northstar id, etc.
                    $reportbackItem['submission_type'] = 'admin';
                    // Create the Event.
                    $event = Event::create([
                        // 'signup_id' => $post->signup_id,
                        // Do we want the user's northstar id or the admin's?
                        'northstar_id' => $rb->northstar_id,
                        'event_type' => $reportbackItem['event_type'],
                        'submission_type' => $reportbackItem['submission_type'],
                        // When we start tracking when admins update the below, we'll need to update this endpoint and comment these in.
                        // 'quantity' => ,
                        // 'quantity_pending' => ,
                        // 'why_participated' => ,
                        // 'caption' => ,
                        'status' => $reportbackItem['status'],
                        // 'source' => ,
                        // 'remote_addr' => ,
                        // 'reason' => ,
                    ]);

                    dd($event);
                    // Create the Review.
                    Review::create([
                        'event_id' => $event->id,
                        // 'signup_id' => $post->signup_id,
                        'northstar_id' => $rb->northstar_id,
                        'admin_northstar_id' => $reportbackItem['reviewer'],
                        'status' => $reportbackItem['status'],
                        'old_status' => $rbItem->status,
                        'comment' => isset($reportbackItem['comment']) ? $reportbackItem['comment'] : null,
                        'created_at' => $event->created_at,
                        'updated_at' => $event->updated_at,
                        'postable_id' => $rbItem->id,
                        'postable_type' => 'Reportback Item',
                    ]);


                    $rbItem->status = $reportbackItem['status'];
                    $rbItem->reviewer = $reportbackItem['reviewer'];
                    $rbItem->save();

                    array_push($reportbackItems, $rbItem);
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }

        return $reportbackItems;
    }
}
