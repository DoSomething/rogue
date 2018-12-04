<?php

namespace Tests\Console;

use Carbon\Carbon;
use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Models\Campaign;

class UpdateSignupAndPostCampaignIdsCommandTest extends TestCase
{

    public function testUpdatingSignupAndPostCampaignIds()
    {
        // Create campaign and set a campaign_run_id.
        $campaign = factory(Campaign::class)->create();
        // Assign campaign id so we can be sure signup and post below do not have the same id to start.
        $campaign->id = 99;
        $campaign->campaign_run_id = 1;
        $campaign->save();

        // Create a signup and two posts that belong to this signup with a run_id = 1.
        $post = factory(Post::class)->create();
        $post->campaign_id = 2;
        $post->save();

        $post2 = factory(Post::class)->create();
        $signup = $post->signup;

        $signup->campaign_id = 2;
        $signup->campaign_run_id = 1;
        $signup->save();

        $post2->signup_id = $signup->id;
        $post2->campaign_id = 2;
        $post2->save();

        // Run script to update signup and post's campaign id.
        $this->artisan('rogue:updatesignupandpostcampaignids');

        // Make sure the signup and posts were updated.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $campaign->id,
            'campaign_run_id' => 1,
            'why_participated' => $signup->why_participated,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'signup_id' => $signup->id,
            'campaign_id' => $campaign->id,
            'northstar_id' => $post->northstar_id,
            'text' => $post->text,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post2->id,
            'signup_id' => $signup->id,
            'campaign_id' => $campaign->id,
            'northstar_id' => $post2->northstar_id,
            'text' => $post2->text,
        ]);
    }
}
