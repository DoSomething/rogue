<?php

namespace Rogue\Http\Transformers\Three;

use Rogue\Models\Signup;
use League\Fractal\TransformerAbstract;

class SignupTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Signup $signup
     * @return array
     */
    public function transform(Signup $signup)
    {
        // @TODO - This is temporary. We have migrated data that has stored quanity in the
        // quanity_pending column on the signup. However, since then we updated the business
        // logic to store everything in the quanity column and not use the quanity_pending
        // column at all. We only want to return what is in the quanity_pending column
        // if is the only place quanity is stored.
        if (! is_null($signup->quantity_pending) && is_null($signup->quantity)) {
            $quantity = $signup->quantity_pending;
        } else {
            $quantity = $signup->quantity;
        }

        return [
            'id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'campaign_run_id' => $signup->campaign_run_id,
            'quantity' => $quantity,
            'why_participated' => $signup->why_participated,
            'source' => $signup->source,
            'details' => $signup->details,
            'created_at' => $signup->created_at->toIso8601String(),
            'updated_at' => $signup->updated_at->toIso8601String(),
        ];
    }
}
