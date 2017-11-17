-<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Rogue\Models\Signup;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Components\Login;

class FilterPostsTest extends DuskTestCase
{
    /**
     * Test filtering by "Pending" returns accepted posts.
     */
    public function testFilterPendingPosts()
    {
        // Create a signup so that the campaign over page will load.
        $signup = factory(Signup::class)->create();
        $signup->posts()->save(factory(Post::class)->make());

        $this->browse(function (Browser $browser) use ($signup) {
            $browser->visit(new HomePage)
                    ->within(new Login, function($browser) {
                        $browser->login($browser);
                    })
                    ->assertPathIs('/campaigns');
                    ->click('@login-button')
                    ->assertPathIs('/register')
                    ->clickLink('Log In')
                    ->type('username', env('NORTHSTAR_EMAIL'))
                    ->type('password', env('NORTHSTAR_PASSWORD'))
                    ->press('Log In')
                    ->assertPathIs('/campaigns');
                    ->visit('/campaigns' . $signup->campaign_id)
                    ->assertSee('Post Filters');
                    // ->clickLink('User Search')
                    // ->assertPathIs('/users')
                    // ->on(new UserSearchPage)
                    // ->assertSeeIn('@title', 'Users')
                    // ->type('@search', 'taylor')
                    // ->press('Submit')
                    // ->assertSeeIn('@messages', 'No user found!');
        });
    }
}
