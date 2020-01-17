<?php

namespace Tests;

use Rogue\Models\User;

trait WithAuthentication
{
    /**
     * Create an administrator & log them in to the application.
     *
     * @return $this
     */
    public function actingAsAdmin()
    {
        $user = factory(User::class, 'admin')->create();

        return $this->actingAs($user);
    }
}
