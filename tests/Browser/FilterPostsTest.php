<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Rogue\Models\Signup;
use Rogue\Models\Post;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\CampaignSinglePage;
// use Tests\Browser\Components\Login;

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
                    // ->within(new Login, function($browser) {
                    //     $browser->login($browser);
                    // })
                    ->click('@login-button')
                    ->assertPathIs('/register')
                    ->clickLink('Log In')
                    ->type('username', env('NORTHSTAR_EMAIL'))
                    ->type('password', env('NORTHSTAR_PASSWORD'))
                    ->press('Log In')
                    ->assertPathIs('/campaigns')
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->screenshot('hi');
                    // ->assertSee('Post Filters')
                    // ->select('option', 'pending')
                    // ->press('Apply Filters')
        });
    }
}
