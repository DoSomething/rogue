<?php

namespace Rogue\Observers;

use Rogue\Models\Signup;

class SignupObserver
{

    /**
     * Handle the Signup "creating" event.
     *
     * @param  \Rogue\Models\Signup  $signup
     * @return void
     */
    public function creating(Signup $signup)
    {
    }
}
