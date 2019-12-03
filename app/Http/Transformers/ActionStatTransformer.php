<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\ActionStat;
use League\Fractal\TransformerAbstract;

class ActionStatTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Action $action
     * @return array
     */
    public function transform(ActionStat $stat)
    {
        return [
            'id' => $stat->id,
            'action_id' => $stat->action_id,
            'school_id' => $stat->school_id,
            'accepted_quantity' => $stat->accepted_quantity,
        ];
    }
}
