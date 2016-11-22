<?php

namespace Rogue\Services;

use \DoSomething\Gateway\Northstar;

class Registrar
{
    /**
     * Create new Registrar instance.
     *
     */
    public function __construct()
    {
        $this->northstar = gateway('northstar');
    }

    public function find($northstar_id)
    {
        // First look in cache
        // If not look to Northstar and stor in cache
        $northstarUser = $this->northstar->getUser('id', $northstar_id);

        // dd($northstarU);
        if (! $northstarUser) {
            // throw new NorthstarUserNotFoundException;
            // @TODO - Handle case where we do not find the user.
        }

        // @TODO: Can't use Repository method below because it throws exception
        // and here we just need "null" if user not found in Database. Find a
        // better fix if necessary!
        // $user = User::find($northstarUser->id);

        // if (! $user) {
        //     return $northstarUser;
        // }

        // return $user;
        return $northstarUser;
    }
}
