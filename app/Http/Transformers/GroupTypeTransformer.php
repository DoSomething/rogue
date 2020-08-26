<?php

namespace Rogue\Http\Transformers;

use League\Fractal\TransformerAbstract;
use Rogue\Models\GroupType;

class GroupTypeTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\GroupType $groupType
     * @return array
     */
    public function transform(GroupType $groupType)
    {
        return [
            'id' => $groupType->id,
            'name' => $groupType->name,
            'filter_by_location' => $groupType->filter_by_location,
            'created_at' => $groupType->created_at->toIso8601String(),
            'updated_at' => $groupType->updated_at->toIso8601String(),
        ];
    }
}
