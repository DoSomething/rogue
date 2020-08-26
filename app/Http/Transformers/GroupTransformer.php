<?php

namespace Rogue\Http\Transformers;

use League\Fractal\TransformerAbstract;
use Rogue\Models\Group;

class GroupTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Group $group
     * @return array
     */
    public function transform(Group $group)
    {
        return [
            'id' => $group->id,
            'group_type_id' => $group->group_type_id,
            'name' => $group->name,
            'city' => $group->city,
            'location' => $group->location,
            'school_id' => $group->school_id,
            'goal' => $group->goal,
            'created_at' => $group->created_at->toIso8601String(),
            'updated_at' => $group->updated_at->toIso8601String(),
            'cursor' => $group->getCursor(),
        ];
    }
}
