<?php

namespace Rogue\Http\Transformers;

use League\Fractal\TransformerAbstract;
use Rogue\Models\Club;

class ClubTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Club $club
     * @return array
     */
    public function transform(Club $club)
    {
        return [
            'id' => $club->id,
            'name' => $club->name,
            'leader_id' => $club->leader_id,
            'city' => $club->city,
            'location' => $club->location,
            'school_id' => $club->school_id,
            'created_at' => $club->created_at->toIso8601String(),
            'updated_at' => $club->updated_at->toIso8601String(),
            'cursor' => $club->getCursor(),
        ];
    }
}
