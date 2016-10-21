<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Reaction;
use League\Fractal\TransformerAbstract;

class ReactionTransformer extends TransformerAbstract
{
    /**
     * Transform the resource data.
     *
     * @param \Rogue\Models\Reaction $reaction
     * @return array
     */
    public function transform(Reaction $reaction)
    {
        // dd($reaction);
        return [
            'reaction_id' => $reaction->id,
            'northstar_id' => $reaction->northstar_id,
            'reportback_item_id' => $reaction->reportback_item_id,
            'created_at' => $reaction->created_at,
            'updated_at' => $reaction->updated_at,
            'deleted_at' => $reaction->deleted_at,
        ];
    }
}
