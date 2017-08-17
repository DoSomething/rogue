<?php

namespace Tests\Services;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Services\Phoenix;
use Rogue\Services\CampaignService;
use Illuminate\Support\Facades\Cache;
use Rogue\Repositories\CacheRepository;

class CampaignServiceTest extends TestCase
{
    /**
     * A test finding a single campaign.
     *s
     * @return void
     */
    public function testFindingASingleCampaign()
    {
        // Create a test campaign to find.
        $testCampaign = [
            'id' => 34,
            'title' => 'Welcome Home',
        ];

        // Mock the call to get the campaign from phoenix.
        $this->mock(Phoenix::class)
            ->shouldReceive('getCampaign')
            ->once()
            ->with($testCampaign['id'])
            ->andReturn([
                'data' => $testCampaign,
            ]);

        $campaignService = $this->app->make(CampaignService::class);

        // Test that the campaign is not in cache.
        $campaign = Cache::get($testCampaign['id']);
        $this->assertNull($campaign);

        // If it's not in cache we should make the call to phoenix
        // and make sure we get back the right campaign and store it in cache
        // for future requests.
        $campaign = $campaignService->find($testCampaign['id']);
        $this->assertEquals($campaign, $testCampaign);
        $this->assertEquals(Cache::get($testCampaign['id']), $testCampaign);
    }

    /**
     * A test finding a group of campaigns.
     *
     * @test
     */
    public function testFindingMultipleCampaigns()
    {
        // @TODO: Doesn't work with in-memory cache, which is used in tests.
        $this->markTestIncomplete();

        $testIds = [57, 4];

        $testCampaigns = [
            [
                'id' => 57,
                'title' => 'Babysitters Club',
            ],
            [
                'id' => 4,
                'title' => 'Banned Books Club',
            ],
        ];

        // Mock the call to get the campaign from phoenix.
        $this->mock(Phoenix::class)
            ->shouldReceive('getAllCampaigns')
            ->once()
            ->andReturn(['data' => $testCampaigns]);

        $campaignService = $this->app->make(CampaignService::class);
        $cache = $this->app->make(CacheRepository::class);

        // Test that the campaign is not in cache.
        $campaigns = $cache->retrieveMany($testIds);
        $this->assertNull($campaigns);

        // If it's not in cache we should make the call to phoenix
        // and make sure we get back the right campaign and store it in cache
        // for future requests.
        $campaigns = $campaignService->findAll($testIds);
        $cachedCampaigns = $cache->retrieveMany($testIds);

        foreach ($testCampaigns as $key => $test) {
            // Make sure the campaign was returned from phoenix.
            $this->assertEquals($test, $campaigns[$key]);

            // Make sure the campaigns got stored in cached.
            $this->assertEquals($test, $cachedCampaigns[$test['id']]);
        }
    }

    public function testGetCampaignPostStatusCounts()
    {
        $testCampaign = [
            'id' => 57,
            'title' => 'Babysitters Club',
        ];

        // Create 10 signups for the campaign.
        factory(Signup::class, 10)->create(['campaign_id' => $testCampaign['id']])
            ->each(function (Signup $signup) {
                // And for each signup, make 3 accepted, 3 rejected, and 3 pending posts.
                $signup->posts()->saveMany(factory(Post::class, 3)->make());
                $signup->posts()->saveMany(factory(Post::class, 'accepted', 3)->make());
                $signup->posts()->saveMany(factory(Post::class, 'rejected', 3)->make());
            });

        $campaignService = $this->app->make(CampaignService::class);
        $campaignCounts = $campaignService->getPostTotals($testCampaign);

        $this->assertEquals($campaignCounts->campaign_id, 57);
        $this->assertEquals($campaignCounts->accepted_count, 30);
        $this->assertEquals($campaignCounts->pending_count, 30);
        $this->assertEquals($campaignCounts->rejected_count, 30);
    }
}
