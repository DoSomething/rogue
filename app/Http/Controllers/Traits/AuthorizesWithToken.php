<?php

namespace Rogue\Http\Controllers\Traits;

use Rogue\Models\Post;

trait AuthorizesWithToken
{
    /**
     * Determine if the full object should be displayed.
     *
     * @param  Illuminate\Contracts\Auth\Authenticatable $user
     * @param  $model
     * @return bool
     */
    public function allowOwnerStaffOrMachine($user, $model)
    {
        // If this is a machine token, show full post:
        if (token()->exists() && ! token()->id()) {
            return true;
        }

        // If this is an anonymous request, only show public fields:
        if ($user == null) {
            return false;
        }

        // Otherwise, only allow staffers & model owner to see full object:
        return is_staff_user() || $user->northstar_id === $model->northstar_id;
    }
}
