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
            $this->login($browser);

            $browser->assertAuthenticated()
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->clickLink('Review')
                    ->assertPathIs('/campaigns/' . $signup->campaign_id . '/inbox')
                    ->on(new CampaignInboxPage($signup->campaign_id))
                    ->waitFor('@acceptButton')
                    ->assertSeeIn('.disabled', 'Tags')
                    ->press('Accept')
                    ->waitFor('@activeAcceptButton', 10)
                    ->assertSeeIn('@activeAcceptButton', 'Accept')
                    ->assertSeeIn('.enabled', 'Tags')
                    ->press('Good Photo')
                    ->waitFor('@activeTagButton', 10)
                    ->assertSeeIn('@activeTagButton', 'Good Photo');
        });
    }

    /**
     * Test user flow of rejecting a pending Post and tagging it as a Test.
     *
     * @group ReviewPost
     * @return void
     */
    public function testRejectingAndTaggingAPost()
    {
        // Create a signup and an associated post with a 'pending' status
        // so there will be a post in the campaign inbox.
        $signup = factory(Signup::class)->create();
        $post = $this->createAssociatedPostWithStatus($signup, 'pending');

        $this->browse(function (Browser $browser) use ($signup) {
            $browser->visit(new HomePage)
                    // We're already logged in from the first test.
                    ->assertAuthenticated()
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->clickLink('Review')
                    ->assertPathIs('/campaigns/' . $signup->campaign_id . '/inbox')
                    ->on(new CampaignInboxPage($signup->campaign_id))
                    ->waitFor('@rejectButton')
                    ->assertSeeIn('.disabled', 'Tags')
                    ->press('Reject')
                    ->waitFor('@activeRejectButton', 10)
                    ->assertSeeIn('@activeRejectButton', 'Reject')
                    ->assertSeeIn('.enabled', 'Tags')
                    ->press('Test')
                    ->waitFor('@activeTagButton', 10)
                    ->assertSeeIn('@activeTagButton', 'Test');
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
                    ->assertAuthenticated()
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->clickLink('Review')
                    ->assertPathIs('/campaigns/' . $signup->campaign_id . '/inbox')
                    ->on(new CampaignInboxPage($signup->campaign_id))
                    ->waitFor('@rejectButton')
                    ->press('Reject')
                    ->waitFor('@activeRejectButton', 10)
                    ->assertSeeIn('@activeRejectButton', 'Reject')
                    ->assertDontSee('@tagButton', 'Good Photo')
                    ->press('Accept')
                    ->waitFor('@activeAcceptButton')
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
