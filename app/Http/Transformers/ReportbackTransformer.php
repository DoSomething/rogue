<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Reportback;
use League\Fractal\TransformerAbstract;

class ReportbackTransformer extends TransformerAbstract
{

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'reportback_items',
    ];

    /**
     * Transform resource data.
     *
     * @param \Rogue\Models Reportback $reportback
     * @return array
     */
    public function transform(Reportback $reportback)
    {
        return [
            'id' => (string) $reportback->id,
            'northstar_id' => $reportback->northstar_id,
            'drupal_id' => $reportback->drupal_id,
            'campaign_id' => $reportback->campaign_id,
            'campaign_run_id' => $reportback->campaign_run_id,
            'quantity' => (int) $reportback->quantity,
            'why_participated' => $reportback->why_participated,
            'num_participants' => $reportback->num_participants,
            'flagged' => $reportback->flagged,
            'flagged_reason' => $reportback->flagged_reason,
            'promoted' => $reportback->promoted,
            'promoted_reason' => $reportback->promoted_reason,
            'created_at' => $reportback->created_at->toIso8601String(),
            'updated_at' => $reportback->updated_at->toIso8601String(),
        ];
    }

    /**
     * Include Reportback Items
     *
     * @param \Rogue\Models Reportback $reportback
     * @return \League\Fractal\Resource\Collection
     */
    public function includeReportbackItems(Reportback $reportback)
    {
        $items = $reportback->items;

        return $this->collection($items, new ReportbackItemTransformer);
    }
}
