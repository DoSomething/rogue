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
            'northstar_id' => $reaction->northstar_id,
            'post_id' => (string) $reaction->post_id,
            'created_at' => $reaction->created_at->toIso8601String(),
            'updated_at' => $reaction->updated_at->toIso8601String(),
            'deleted_at' => is_null($reaction->deleted_at) ? null : $reaction->deleted_at->toIso8601String(),
        ];
    }
}
