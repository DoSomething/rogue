<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\ReportbackItem;
use League\Fractal\TransformerAbstract;

class ReportbackItemTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models Reportback $reportback
     * @return array
     */
    public function transform(ReportbackItem $reportbackItem)
    {
        return [
            'id' => (string) $reportbackItem->id,
            'reportback_id' => $reportbackItem->reportback_id,
            'file_id' => $reportbackItem->file_id,
            'caption' => $reportbackItem->caption,
            'status' => $reportbackItem->status,
            'reviewed' => $reportbackItem->reviewed,
            'reviewer' => $reportbackItem->reviewer,
            'review_source' => $reportbackItem->review_source,
            'source' => $reportbackItem->source,
            'remote_addr' => $reportbackItem->remote_addr,
            'created_at' => $reportbackItem->created_at->toIso8601String(),
            'updated_at' => $reportbackItem->updated_at->toIso8601String(),
        ];
    }
}
