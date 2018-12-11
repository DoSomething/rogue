<?php

namespace Rogue\Policies;

use Rogue\Models\Signup;
use Illuminate\Auth\Access\HandlesAuthorization;
use Rogue\Http\Controllers\Traits\AuthorizesWithToken;

class SignupPolicy
{
    use AuthorizesWithToken;
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the full signup should be displayed.
     *
     * @param  Illuminate\Contracts\Auth\Authenticatable $user
     * @param  Rogue\Models\Signup $signup
     * @return bool
     */
    public function viewAll($user, Signup $signup)
    {
        return $this->allowOwnerStaffOrMachine($user, $signup);
    }
}
