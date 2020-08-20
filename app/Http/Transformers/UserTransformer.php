<?php

namespace Rogue\Http\Transformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\User $user
     *
     * @return array
     */
    public function transform($user)
    {
        // @TODO: We should just remove the ability to `?include` users...
        return $user ? $user->toArray() : null;
    }
}
