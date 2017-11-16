<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Rogue\Models\Signup;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\UserPage;
use Tests\Browser\Pages\UserSearchPage;

class UserSearchTest extends DuskTestCase
{
    /**
     * Test visiting the User Search Page.
     * Searching for a user by name (instead of
     * valid email address) returns "No user found!" message.
     */
    public function testIncorrectUserSearchPage()
    {
        // Create a signup so that the campaign over page will load.
        factory(Signup::class)->create();

        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                    ->click('@login-button')
                    ->assertPathIs('/register')
                    ->clickLink('Log In')
                    ->type('username', env('NORTHSTAR_EMAIL'))
                    ->type('password', env('NORTHSTAR_PASSWORD'))
                    ->press('Log In')
                    ->assertPathIs('/campaigns')
                    ->clickLink('User Search')
                    ->assertPathIs('/users')
                    ->on(new UserSearchPage)
                    ->assertSeeIn('@title', 'Users')
                    ->type('@search', 'taylor')
                    ->press('Submit')
                    ->assertSeeIn('@messages', 'No user found!');
        });
    }

    /**
     * Test visiting the User Search Page.
     * Searching for a user by valid email
     * redirects to the user's page.
     */
    public function testCorrectUserSearchPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                    // We're already logged in from the first test.
                    ->assertPathIs('/campaigns')
                    ->clickLink('User Search')
                    ->assertPathIs('/users')
                    ->on(new UserSearchPage)
                    ->assertSeeIn('@title', 'Users')
                    ->type('@search', env('NORTHSTAR_EMAIL'))
                    ->press('Submit')
                    ->on(new UserPage)
                    ->assertSeeIn('@title', 'User')
                    ->assertSee(env('NORTHSTAR_EMAIL'));
        });
    }
}
