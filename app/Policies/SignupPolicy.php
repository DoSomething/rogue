<?php

namespace Rogue\Policies;

use Rogue\Models\Signup;
use Illuminate\Auth\Access\HandlesAuthorization;

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
