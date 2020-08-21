<?php

namespace Rogue\Http\Transformers;

use League\Fractal\TransformerAbstract;
use Rogue\Models\ActionStat;

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
            'id' => $actionStat->id,
            'action_id' => $actionStat->action_id,
            'school_id' => $actionStat->school_id,
            'location' => $actionStat->location,
            'impact' => $actionStat->impact,
            'created_at' => $actionStat->created_at->toIso8601String(),
            'updated_at' => $actionStat->updated_at->toIso8601String(),
            'cursor' => $actionStat->getCursor(),
        ];
    }
}
