<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\UserSearchPage;
use Tests\Browser\Pages\HomePage;

class UserSearchTest extends DuskTestCase
{
    /**
     * Test visiting the User Search Page.
     * Searching for a user by name (instead of
     * valid email address) returns "No user found!" message.
     */
    public function testIncorrectUserSearchPage()
    {
        // $this->browse(function (Browser $browser) {
        //     $browser->login()
        //         ->visit(new UserSearchPage)
        //         ->assertSeeIn('@title', 'Users');
                // ->type('@search', 'taylor')
                // ->press('Submit')
                // ->assertSeeIn('@messages', 'No user found!');
        // });

        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                    ->click('@login-button')
                    ->dump();
                    // ->assertPathIs('https://northstar-qa.dosomething.org/register');
                    // ->type('username', 'clee@dosomething.org')
                    // ->type('password', ENV)
                    // ->press('Log In')
                    // ->assertPathIs('/campaigns');
        });
    }
}


// $this->browse(function ($first, $second) {
//     $first->loginAs(User::find(1))
//           ->visit('/home');
// });


// $browser->visit(new HomePage);
