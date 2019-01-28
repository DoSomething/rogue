<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Action;
use Rogue\Models\Signup;
use Rogue\Models\Campaign;
use Rogue\Services\CampaignService;

class CampaignServiceTest extends TestCase
{
    public function testGetCampaignPostStatusCounts()
    {
        $testCampaign = factory(Campaign::class)->create([
                                                    'id' => 57,
                                                    'internal_title' => 'Babysitters Club',
                                                ]);

        // Create 10 signups for the campaign.
        factory(Signup::class, 10)->create(['campaign_id' => $testCampaign['id']])
            ->each(function (Signup $signup) use ($testCampaign) {
                // And for each signup, make 3 accepted, 3 rejected, and 3 pending posts.
                $signup->posts()->saveMany(factory(Post::class, 3)->make([
                                                                            'campaign_id' => $testCampaign['id'],
                                                                            'action_id' => factory(Action::class)->create([
                                                                                'campaign_id' => $testCampaign['id'],
                                                                            ]),
                                                                        ]));
                $signup->posts()->saveMany(factory(Post::class, 'accepted', 3)->make([
                                                                                    'campaign_id' => $testCampaign['id'],
                                                                                    'action_id' => factory(Action::class)->create([
                                                                                        'campaign_id' => $testCampaign['id'],
                                                                                    ]),
                                                                                ]));
                $signup->posts()->saveMany(factory(Post::class, 'rejected', 3)->make([
                                                                                'campaign_id' => $testCampaign['id'],
                                                                                'action_id' => factory(Action::class)->create([
                                                                                    'campaign_id' => $testCampaign['id'],
                                                                                ]),
                                                                            ]));
            });

        $campaignService = $this->app->make(CampaignService::class);
        $campaignCounts = $campaignService->getPostTotals($testCampaign);

        $this->assertEquals($campaignCounts->campaign_id, 57);
        $this->assertEquals($campaignCounts->accepted_count, 30);
        $this->assertEquals($campaignCounts->pending_count, 30);
        $this->assertEquals($campaignCounts->rejected_count, 30);
    }
}
