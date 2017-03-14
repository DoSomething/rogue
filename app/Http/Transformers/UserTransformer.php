<?php

namespace Rogue\Http\Transformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Photo $user
     * @return array
     */
    public function transform($user)
    {
        return [
            'first_name' => $user->first_name,
        ];
    }
}
