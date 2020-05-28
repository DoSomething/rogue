<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\GroupType;
use League\Fractal\TransformerAbstract;

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
            'created_at' => $groupType->created_at->toIso8601String(),
            'updated_at' => $groupType->updated_at->toIso8601String(),
        ];
    }
}
