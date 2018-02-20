<?php

namespace Tests\Browser;

use Rogue\Models\Post;
use Tests\DuskTestCase;
use Rogue\Models\Signup;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\CampaignInboxPage;
use Tests\Browser\Pages\CampaignSinglePage;

class HistoryModalTest extends DuskTestCase
{
    /**
     * Test user flow of updating a post's quantity in the history modal on the campaign inbox page.
     *
     * @group HistoryModal
     * @return void
     */
    public function testUpdatingAPostsQuantityOnCamapaignInboxPageWhenV3QuantitySupportIsTrue()
    {
        // Create a signup and an associated post with a 'pending' status
        // so there will be a post in the campaign inbox.
        $signup = factory(Signup::class)->create();
        $post = $this->createAssociatedPostWithStatus($signup, 'pending');
        $post->quantity = '2';
        $post->save();

        $this->browse(function (Browser $browser) use ($signup) {
            $this->login($browser);

            $browser->assertAuthenticated()
                    ->visit('/campaigns/' . $signup->campaign_id)
                    ->on(new CampaignSinglePage($signup->campaign_id))
                    ->clickLink('Review')
                    ->assertPathIs('/campaigns/' . $signup->campaign_id . '/inbox')
                    ->on(new CampaignInboxPage($signup->campaign_id))
                    ->waitFor('@acceptButton')
                    ->assertSeeIn('@quantityBox', '2')
                    ->screenshot('before clicking link')
                    ->clickLink('Edit | Show History')
                    ->waitForText('New Quantity')
                    ->type('@newQuantityForm', '3')
                    ->screenshot('before clicking save')
                    ->press('SAVE')
                    ->screenshot('after clicking save')
                    // ->pause(5000)
                    ->assertSeeIn('@quantityBox', '3');
        });
    }

    /**
     * Test user flow of updating a post's quantity in the history modal on the campaign overview page.
     *
     * @group HistoryModal
     * @return void
     */
    // public function testUpdatingAPostsQuantityOnCamapaignOverviewPageWhenV3QuantitySupportIsTrue()
    // {
    //     // Create a signup and an associated post with a 'pending' status
    //     // so there will be a post in the campaign inbox.
    //     $signup = factory(Signup::class)->create();
    //     $post = $this->createAssociatedPostWithStatus($signup, 'accepted');
    //     $post->quantity = '2';
    //     $post->save();

    //     $this->browse(function (Browser $browser) use ($signup) {
    //         $this->login($browser);

    //         $browser->assertAuthenticated()
    //                 ->visit('/campaigns/' . $signup->campaign_id)
    //                 ->on(new CampaignSinglePage($signup->campaign_id))
    //                 ->waitFor('@activeAcceptButton')
    //                 ->assertSeeIn('@quantityBox', '2')
    //                 ->screenshot('before clicking link')
    //                 ->clickLink('Edit | Show History')
    //                 ->waitForText('New Quantity')
    //                 ->type('@newQuantityForm', '3')
    //                 ->screenshot('before clicking save')
    //                 ->press('SAVE')
    //                 ->screenshot('after clicking save')
    //                 ->assertSeeIn('@quantityBox', '3');
    //     });
    // }


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
