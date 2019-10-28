<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Campaign;
use League\Fractal\TransformerAbstract;

class CampaignTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Campaign $campaign
     * @return array
     */
    public function transform(Campaign $campaign)
    {
        return [
            'id' => $campaign->id,
            'internal_title' => $campaign->internal_title,
            'start_date' => $campaign->start_date->toIso8601String(),
            'end_date' => optional($campaign->end_date)->toIso8601String(),
            'is_open' => $campaign->isOpen(),
            'impact_doc' => $campaign->impact_doc,
            'cause' => $campaign->cause,
            'pending_count' => $campaign->pending_count,
            'cause_names' => $campaign->getCauseNames(),
            'created_at' => $campaign->created_at->toIso8601String(),
            'updated_at' => $campaign->updated_at->toIso8601String(),
            'cursor' => base64_encode($campaign->id),
        ];
    }
}
