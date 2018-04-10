<?php

namespace Tests\Browser;

use Rogue\Models\Post;
use Tests\DuskTestCase;
use Rogue\Models\Signup;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\CampaignSinglePage;

// class FilterPostsTest extends DuskTestCase
// {
//     /**
//      * Test filtering by "Pending" returns pending posts.
//      */
//     public function testFilterPendingPosts()
//     {
//         // Create a signup so that the campaign overview page will load.
//         $signup = factory(Signup::class)->create();

//         // Create a post so the filter will show results.
//         $this->createAssociatedPostWithStatus($signup, 'pending');

//         $this->browse(function (Browser $browser) use ($signup) {
//             $this->login($browser);

//             $browser->assertAuthenticated()
//                     ->visit('/campaigns/' . $signup->campaign_id)
//                     ->on(new CampaignSinglePage($signup->campaign_id))
//                     ->assertSee('Post Filters')
//                     ->select('select', 'pending')
//                     ->assertSelected('select', 'pending')
//                     ->press('Apply Filters')
//                     ->waitFor('@unactiveAcceptButton')
//                     ->assertDontSee('@activeAcceptButton', 'Accept');
//         });
//     }

//     /**
//      * Test filtering by "Rejected" returns rejected posts.
//      */
//     public function testFilterRejectedPosts()
//     {
//         // Create a signup and an associated post with a 'rejected' status
//         // so the filter will show results.
//         $signup = factory(Signup::class)->create();
//         $this->createAssociatedPostWithStatus($signup, 'rejected');

//         $this->browse(function (Browser $browser) use ($signup) {
//             $browser->visit(new HomePage)
//                     // We're already logged in from the first test.
//                     ->assertAuthenticated()
//                     ->visit('/campaigns/' . $signup->campaign_id)
//                     ->on(new CampaignSinglePage($signup->campaign_id))
//                     ->assertSee('Post Filters')
//                     ->select('select', 'rejected')
//                     ->assertSelected('select', 'rejected')
//                     ->press('Apply Filters')
//                     ->waitFor('@activeRejectButton')
//                     ->assertDontSee('@activeAcceptButton', 'Accept')
//                     ->assertSeeIn('@activeRejectButton', 'Reject');
//         });
//     }

//     /**
//      * Test filtering by "Accepted" returns accepted posts.
//      */
//     public function testFilterAcceptedPosts()
//     {
//         // Create a signup and an associated post with a 'accepted' status
//         // so the filter will show results.
//         $signup = factory(Signup::class)->create();
//         $this->createAssociatedPostWithStatus($signup, 'accepted');

//         $this->browse(function (Browser $browser) use ($signup) {
//             $browser->visit(new HomePage)
//                     // We're already logged in from the first test.
//                     ->assertAuthenticated()
//                     ->visit('/campaigns/' . $signup->campaign_id)
//                     ->on(new CampaignSinglePage($signup->campaign_id))
//                     ->assertSee('Post Filters')
//                     ->select('select', 'accepted')
//                     ->assertSelected('select', 'accepted')
//                     ->press('Apply Filters')
//                     ->waitFor('@activeAcceptButton')
//                     ->assertDontSee('@activeRejectButton', 'Reject')
//                     ->assertSeeIn('@activeAcceptButton', 'Accept');
//         });
//     }

//     /**
//      * Test filtering by tag.
//      */
//     public function testFilterByTag()
//     {
//         // Create a signup and an associated post with a 'accepted' status
//         // so the filter will show results.
//         $signup = factory(Signup::class)->create();
//         $post = $this->createAssociatedPostWithStatus($signup, 'accepted');
//         $post->tag('Good Submission');

//         $this->browse(function (Browser $browser) use ($signup) {
//             $browser->visit(new HomePage)
//                     // We're already logged in from the first test.
//                     ->assertAuthenticated()
//                     ->visit('/campaigns/' . $signup->campaign_id)
//                     ->on(new CampaignSinglePage($signup->campaign_id))
//                     ->assertSee('Post Filters')
//                     ->select('select', 'accepted')
//                     ->assertSelected('select', 'accepted')
//                     ->press('Good Submission')
//                     ->press('Apply Filters')
//                     ->waitFor('@activeTagButton')
//                     ->assertDontSee('@activeTagButton', 'Good Quote')
//                     ->assertSeeIn('@activeTagButton', 'Good Submission');
//         });
//     }

//     /**
//      * Test no results page.
//      */
//     public function testNoResultsPage()
//     {
//         // Create a signup and an associated post with a 'accepted' status
//         // so the filter will show results.
//         $signup = factory(Signup::class)->create();
//         $post = $this->createAssociatedPostWithStatus($signup, 'accepted');

//         $this->browse(function (Browser $browser) use ($signup) {
//             $browser->visit(new HomePage)
//                     // We're already logged in from the first test.
//                     ->assertAuthenticated()
//                     ->visit('/campaigns/' . $signup->campaign_id)
//                     ->on(new CampaignSinglePage($signup->campaign_id))
//                     ->assertSee('Post Filters')
//                     ->select('select', 'accepted')
//                     ->assertSelected('select', 'accepted')
//                     ->press('Good For Storytelling')
//                     ->press('Apply Filters')
//                     ->waitFor('.empty__text')
//                     ->assertSee('There are no results!');
//         });
//     }

//     /**
//      * Helper function to create a post with a specific status and associate it with a signup.
//      */
//     private function createAssociatedPostWithStatus($signup, $status)
//     {
//         $post = $signup->posts()->save(factory(Post::class)->make(['status' => $status]));
//         $post->campaign_id = $signup->campaign_id;
//         $post->save();

//         return $post;
//     }
// }
