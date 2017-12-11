<?php

namespace Tests\Browser;

use Rogue\Models\Post;
use Tests\DuskTestCase;
use Rogue\Models\Signup;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\CampaignInboxPage;
use Tests\Browser\Pages\CampaignSinglePage;

class ReviewPostTest extends DuskTestCase
{
    /**
     * Test user flow of approving a pending Post and tagging it as a Good Photo.
     *
     * @group ReviewPost
     * @return void
     */
    public function testApprovingAndTaggingAPost()
    {
        // Create a signup and an associated post with a 'pending' status
        // so there will be a post in the campaign inbox.
        $signup = factory(Signup::class)->create();
        $post = $this->createAssociatedPostWithStatus($signup, 'pending');

        $this->browse(function (Browser $browser) use ($signup) {
            $browser->visit(new HomePage)
                    ->click('@login-button')
                    ->assertPathIs('/register')
                    ->clickLink('Log In')
                    ->type('username', env('NORTHSTAR_EMAIL'))
                    ->type('password', env('NORTHSTAR_PASSWORD'))
                    ->press('Log In')
                    ->assertPathIs('/campaigns')
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->clickLink('Review')
                    ->assertPathIs('/campaigns/' . $signup->campaign_id . '/inbox')
                    ->on(new CampaignInboxPage($signup->campaign_id))
                    ->pause(3000)
                    ->press('Accept')
                    ->pause(3000)
                    ->assertSeeIn('@activeAcceptButton', 'Accept')
                    ->press('Good Photo')
                    ->pause(3000)
                    ->assertSeeIn('@activeTagButton', 'Good Photo');
        });
    }

    /**
     * Test user flow of rejecting a pending Post.
     *
     * @group ReviewPost
     * @return void
     */
    public function testRejectingAPost()
    {
        // Create a signup and an associated post with a 'pending' status
        // so there will be a post in the campaign inbox.
        $signup = factory(Signup::class)->create();
        $post = $this->createAssociatedPostWithStatus($signup, 'pending');

        $this->browse(function (Browser $browser) use ($signup) {
            $browser->visit(new HomePage)
                    // We're already logged in from the first test.
                    ->assertPathIs('/campaigns')
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->clickLink('Review')
                    ->assertPathIs('/campaigns/' . $signup->campaign_id . '/inbox')
                    ->on(new CampaignInboxPage($signup->campaign_id))
                    ->pause(3000)
                    ->press('Reject')
                    ->pause(3000)
                    ->assertSeeIn('@activeRejectButton', 'Reject')
                    ->assertDontSee('@tagButton', 'Good Photo');
        });
    }

    /**
     * Test user flow of rejecting a pending Post and then accepting it.
     *
     * @group ReviewPost
     * @return void
     */
    public function testRejectingAndAcceptingPost()
    {
        // Create a signup and an associated post with a 'pending' status
        // so there will be a post in the campaign inbox.
        $signup = factory(Signup::class)->create();
        $post = $this->createAssociatedPostWithStatus($signup, 'pending');

        $this->browse(function (Browser $browser) use ($signup) {
            $browser->visit(new HomePage)
                    // We're already logged in from the first test.
                    ->assertPathIs('/campaigns')
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->clickLink('Review')
                    ->assertPathIs('/campaigns/' . $signup->campaign_id . '/inbox')
                    ->on(new CampaignInboxPage($signup->campaign_id))
                    ->pause(3000)
                    ->press('Reject')
                    ->pause(3000)
                    ->assertSeeIn('@activeRejectButton', 'Reject')
                    ->assertDontSee('@tagButton', 'Good Photo')
                    ->press('Accept')
                    ->pause(3000)
                    ->assertSeeIn('@activeAcceptButton', 'Accept')
                    ->assertDontSee('@activeRejectButton', 'Reject');
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

        return $post;
    }
}
