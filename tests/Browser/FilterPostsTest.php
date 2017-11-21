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
     * Test filtering by "Pending" returns pending posts.
     */
    public function testFilterPendingPosts()
    {
        // Create a signup so that the campaign overview page will load.
        $signup = factory(Signup::class)->create();

        // Create a post so the filter will show results.
        $this->createAssociatedPostWithStatus($signup, 'pending');


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
                        ->assertSee('Post Filters')
                        ->select('select', 'pending')
                        ->assertSelected('select', 'pending')
                        ->press('Apply Filters')
                        ->pause(5000)
                        ->assertDontSee('@activeAcceptButton', 'Accept');
            });
        }

    /**
     * Test filtering by "Rejected" returns rejected posts.
     */
    public function testFilterRejectedPosts()
    {
        // Create a signup and an associated post with a 'rejected' status
        // so the filter will show results.
        $signup = factory(Signup::class)->create();
        $this->createAssociatedPostWithStatus($signup, 'rejected');


        $this->browse(function (Browser $browser) use ($signup) {
            $browser->visit(new HomePage)
                    // We're already logged in from the first test.
                    ->assertPathIs('/campaigns')
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->assertSee('Post Filters')
                    ->select('select', 'rejected')
                    ->assertSelected('select', 'rejected')
                    ->press('Apply Filters')
                    ->pause(5000)
                    ->assertSeeIn('@activeRejectButton', 'Reject');
        });
    }

    /**
     * Test filtering by "Accepted" returns accepted posts.
     */
    public function testFilterAcceptedPosts()
    {
        // Create a signup and an associated post with a 'accepted' status
        // so the filter will show results.
        $signup = factory(Signup::class)->create();
        $this->createAssociatedPostWithStatus($signup, 'accepted');

        $this->browse(function (Browser $browser) use ($signup) {
            $browser->visit(new HomePage)
                    // We're already logged in from the first test.
                    ->assertPathIs('/campaigns')
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->assertSee('Post Filters')
                    ->select('select', 'accepted')
                    ->assertSelected('select', 'accepted')
                    ->press('Apply Filters')
                    ->pause(5000)
                    ->assertSeeIn('@activeAcceptButton', 'Accept');
        });
    }

    /**
     * Helper function to create a post with a specific status and associate it with a signup.
     */
    private function createAssociatedPostWithStatus($signup, $status)
    {
        $post = $signup->posts()->save(factory(Post::class)->make(['status' => $status]));
        $post->campaign_id = $signup->campaign_id;
        $post->save();
        // return $post;
    }
}
