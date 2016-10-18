<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use DoSomething\Gateway\Contracts\NorthstarUserContract;
use DoSomething\Gateway\Laravel\HasNorthstarToken;

class User extends Model implements AuthenticatableContract, NorthstarUserContract
{
    use Authenticatable, HasNorthstarToken;

    /**
     * Check to see if this user matches one of the given roles.
     *
     * @param  array|mixed $roles - role(s) to check
     * @return bool
     */
    public function hasRole($roles)
    {
        // Prepare an array of roles to check.
        // e.g. $user->hasRole('admin') => ['admin']
        //      $user->hasRole('admin, 'staff') => ['admin', 'staff']
        $roles = is_array($roles) ? $roles : func_get_args();
        return in_array($this->role, $roles);
    }
}
