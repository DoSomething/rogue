<?php

namespace Rogue\Auth;

use Rogue\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class CustomGate extends Gate implements GateContract
{
    protected function raw($ability, $arguments = [])
    {
        if (! $user = $this->resolveUser()) {
            // return false;
            $user = new User;
        }
        $arguments = Arr::wrap($arguments);
        // First we will call the "before" callbacks for the Gate. If any of these give
        // back a non-null response, we will immediately return that result in order
        // to let the developers override all checks for some authorization cases.
        $result = $this->callBeforeCallbacks(
            $user, $ability, $arguments
        );
        if (is_null($result)) {
            $result = $this->callAuthCallback($user, $ability, $arguments);
        }
        // After calling the authorization callback, we will call the "after" callbacks
        // that are registered with the Gate, which allows a developer to do logging
        // if that is required for this application. Then we'll return the result.
        $this->callAfterCallbacks(
            $user, $ability, $arguments, $result
        );
        return $result;
    }
}
