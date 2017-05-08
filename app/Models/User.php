<?php

namespace Rogue\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DoSomething\Gateway\Laravel\HasNorthstarToken;
use DoSomething\Gateway\Contracts\NorthstarUserContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract, NorthstarUserContract
{
    use Authenticatable, HasNorthstarToken;

    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

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
