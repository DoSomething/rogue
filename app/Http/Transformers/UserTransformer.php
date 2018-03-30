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
        if ($user) {
            $response = [
                'first_name' => $user->first_name,
            ];

            if (is_staff_user() || auth()->id() === $user->id) {
                $response['last_name'] = $user->last_name;
                $response['birthdate'] = $user->birthdate;
                $response['email'] = $user->email;
                $response['mobile'] = $user->mobile;
            }
        }

        return isset($response) ? $response : [];
    }
}
