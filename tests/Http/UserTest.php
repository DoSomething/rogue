<?php

use DoSomething\Gateway\Northstar;
use Rogue\Models\User;

class UserTest extends TestCase
{
    /**
     * Test that an admin user can access a page in the app.
     */
    public function testAuthenticatedUserDoesntGetRedirectedHome()
    {
        $this->actingAsAdmin()
            ->visit('/campaigns');

        $this->see('Campaign Overview');
    }

    /**
     * Test that non-admin can not get into the site.
     *
     * @return void
     */
    public function testUnauthenticatedUserCantAccessPagesInApp()
    {
        $user = factory(User::class)->make();

        $this->actingAs($user)
            ->get('/campaigns');

        $this->assertResponseStatus(403);
    }

    /**
     * Test that northstar authorization is called when hitting the /login route
     *
     * @return void
     */
    public function testLogin()
    {
        $this->mock(Northstar::class)
            ->shouldReceive('authorize');

        $this->visit('/login');
    }

    /**
     * Test that northstar logout method is called when the /logout route is hit.
     *
     * @return void
     */
    public function testLogout()
    {
        $this->mock(Northstar::class)
            ->shouldReceive('logout');

        $this->visit('/logout');
    }
}
