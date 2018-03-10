<?php

namespace Rogue\Http\Transformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\User $user
     * @return array
     */
    public function transform($user)
    {
        if ($user) {
            return [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'birthdate' => $user->birthdate,
                'email' => $user->email,
                'mobile' => $user->mobile,
            ];
        }

        return [];
    }
}
