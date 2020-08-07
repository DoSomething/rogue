<?php

namespace Rogue\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Rogue\Models\Signup;

class SignupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the full signup should be displayed.
     *
     * @param  Illuminate\Contracts\Auth\Authenticatable $user
     * @param  Rogue\Models\Signup $signup
     * @return bool
     */
    public function viewAll($user, Signup $signup)
    {
        return is_staff_user() || is_owner($signup);
    }
}
