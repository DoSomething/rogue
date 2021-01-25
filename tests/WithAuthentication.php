<?php

namespace Tests;

use App\Models\User;

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

    /**
     * Create a staffer & log them in to the application.
     *
     * @return $this
     */
    public function actingAsStaff()
    {
        $user = factory(User::class, 'staff')->create();

        return $this->actingAs($user);
    }
}
