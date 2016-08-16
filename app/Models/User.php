<?php

namespace Rogue\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Check to see if this user matches one of the given roles.
     *
     * @param array|mixed $roles -role(s) to check
     * @return bool
     */
    public function hasRole($roles)
    {
        $roles = is_array($roles) ? $roles: func_get_args();

        return in_array($this->role, $roles);
    }
}
