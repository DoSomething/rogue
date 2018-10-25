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
            'campaign_id' => $campaign->id,
            'internal_title' => $campaign->internal_title,
            'start_date' => $campaign->start_date,
            'end_date' => $campaign->end_date,
            'created_at' => $campaign->created_at,
            'update_at' => $campaign->update_at,
        ];
    }
}
