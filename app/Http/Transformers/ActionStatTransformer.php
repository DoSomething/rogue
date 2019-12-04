<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\ActionStat;
use League\Fractal\TransformerAbstract;

class ActionStatTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\ActionStat $actionStat
     * @return array
     */
    public function transform(ActionStat $actionStat)
    {
        return [
            'action_id' => $actionStat->action_id,
            'school_id' => $actionStat->school_id,
            'accepted_quantity' => $actionStat->accepted_quantity,
            'created_at' => $campaign->created_at->toIso8601String(),
            'updated_at' => $campaign->updated_at->toIso8601String(),
        ];
    }
}
