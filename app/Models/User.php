<?php

namespace Rogue\Models;

use Illuminate\Notifications\Notifiable;
use DoSomething\Gateway\Laravel\HasNorthstarToken;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DoSomething\Gateway\Contracts\NorthstarUserContract;

class User extends Authenticatable implements NorthstarUserContract
{
    use HasNorthstarToken, Notifiable;

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
