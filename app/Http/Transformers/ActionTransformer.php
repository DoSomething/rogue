<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Action;
use League\Fractal\TransformerAbstract;

class ActionTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Action $action
     * @return array
     */
    public function transform(Action $action)
    {
        return [
            'id' => $action->id,
            'name' => $action->name,
            'campaign_id' => $action->campaign_id,
            'post_type' => $action->post_type,
            'callpower_campaign_id' => $action->callpower_campaign_id,
            'reportback' => $action->reportback,
            'civic_action' => $action->civic_action,
            'scholarship_entry' => $action->scholarship_entry,
            'anonymous' => $action->anonymous,
            'noun' => $action->noun,
            'verb' => $action->verb,
            'created_at' => $action->created_at->toIso8601String(),
            'updated_at' => $action->updated_at->toIso8601String(),
        ];
    }
}
