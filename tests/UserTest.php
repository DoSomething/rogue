<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use DoSomething\Gateway\Northstar;
use Rogue\Services\CampaignService;
use Rogue\Models\User;

class UserTest extends TestCase
{
    /**
     * Test that an admin user can access a page in the app.
     *
     * @return void
     */
    public function testAuthenticatedUserDoesntGetRedirectedHome()
    {
        $user = factory(User::class)->make([
            'role' => 'admin',
        ]);

        $mock = $this->mock(CampaignService::class)
            ->shouldReceive('getCampaignIdsFromSignups')->andReturn([])
            ->shouldReceive('findAll')
            ->shouldReceive('appendStatusCountsToCampaigns')
            ->shouldReceive('groupByCause')
            ->andReturn('true');

        $this->actingAs($user)
            ->visit('/campaigns')
            // Only authenticated users will see log out button.
            ->see('Log Out');
    }

    /**
     * Test that non-admin can not get into the site.
     *
     * @return void
     */
    public function testUnauthenticatedUserCantAccessPagesInApp()
    {
        $user = factory(User::class)->make();
        $this->be($user);

        $response = $this->call('GET', '/campaigns');
        $this->assertResponseStatus(403);
    }

    /**
     * Test that northstar authorization is called when hiting the /login route
     *
     * @return void
     */
    public function testLogin()
    {
        $mock = $this->mock(Northstar::class)
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
        $mock = $this->mock(Northstar::class)
            ->shouldReceive('logout');

        $this->visit('/logout');
    }
}
