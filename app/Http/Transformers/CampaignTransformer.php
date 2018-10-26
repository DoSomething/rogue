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
            'start_date' => $campaign->start_date,
            'end_date' => $campaign->end_date,
            'created_at' => $campaign->created_at,
            'updated_at' => $campaign->updated_at,
        ];
    }
}
