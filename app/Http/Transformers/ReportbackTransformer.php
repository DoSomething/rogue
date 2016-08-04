<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Reportback;
use League\Fractal\TransformerAbstract;

class ReportbackTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param User $user
     * @return array
     */
    public function transform(Reportback $reportback)
    {
        return [
            'id' => (string) $reportback->id,
            'northstar_id' => (string) $reportback->northstar_id,
            'drupal_id' => (string) $reportback->drupal_id,
            'campaign_id' => (string) $reportback->campaign_id,
            'campaign_run_id' => (string) $reportback->campaign_run_id,
            'quantity' => (int) $reportback->quantity,
            'why_participated' => (string) $reportback->why_participated,
            'num_participants' => (int) $reportback->num_participants,
            'flagged' => $reportback->flagged,
            'flagged_reason' => (string) $reportback->flagged_reason,
            'promoted' => $reportback->promoted,
            'promoted_reason' => (string) $reportback->promoted_reason,
            'created_at' => $reportback->created_at->toIso8601String(),
            'updated_at' => $reportback->updated_at->toIso8601String(),
        ];
    }
}
