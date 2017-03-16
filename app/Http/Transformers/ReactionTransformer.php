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
            'reactionable_id' => (string) $reaction->reactionable_id,
            'reactionable_type' => $reaction->reactionable_type,
            'created_at' => $reaction->created_at,
            'updated_at' => $reaction->updated_at,
            'deleted_at' => $reaction->deleted_at,
        ];
    }
}
