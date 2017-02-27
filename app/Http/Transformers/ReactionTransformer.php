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
        return [
            'reaction_id' => (string) $reaction->id,
            'northstar_id' => $reaction->northstar_id,
            'postable_id' => (string) $reaction->postable_id,
            'postable_type' => $reaction->postable_type,
            'created_at' => $reaction->created_at,
            'updated_at' => $reaction->updated_at,
            'deleted_at' => $reaction->deleted_at,
            'total_reactions' => $reaction->totalReactions,
        ];
    }
}
