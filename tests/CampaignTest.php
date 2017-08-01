<?php

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Services\Phoenix;
use Rogue\Services\CampaignService;
use Illuminate\Support\Facades\Cache;
use Rogue\Repositories\CacheRepository;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CampiagnsTest extends TestCase
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
        $phoenix = $this->mock(Phoenix::class)
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
     *s
     * @return void
     */
    public function testFindingMultipleCampaigns()
    {
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
        $phoenix = $this->mock(Phoenix::class)
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

        foreach($testCampaigns as $key => $test) {
            // Make sure the campaign was returned from phoenix.
            $this->assertEquals($test, $campaigns[$key]);

            // Make sure the campaigns got stored in cached.
            $this->assertEquals($test, $cachedCampaigns[$test['id']]);
        }
    }

    public function testGetCampaignPostStatusCounts() {
        $testCampaigns = collect([
            [
                'id' => 57,
                'title' => 'Babysitters Club',
            ],
        ]);

        $statuses = ['accepted', 'pending', 'rejected'];

        // Create 10 signups for a single campaign.
        $signups = factory(Signup::class, 10)->create(['campaign_id' => $testCampaigns[0]['id']])
            ->each(function ($signup) use ($statuses) {
                // For each signup, create 3 accepted, 3 pending, and 3 rejected posts.
                foreach ($statuses as $status) {
                     $post = factory(Post::class, 3)->create([
                        'signup_id' => $signup->id,
                        'northstar_id' => $signup->northstar_id,
                        'status' =>  $status,
                    ]);
                }
            });


        $campaignService = $this->app->make(CampaignService::class);
        $campaignCounts = $campaignService->getPostTotals($testCampaigns[0]);

        $this->assertEquals($campaignCounts->get($testCampaigns[0]['id'])->accepted_count, 30);
        $this->assertEquals($campaignCounts->get($testCampaigns[0]['id'])->pending_count, 30);
        $this->assertEquals($campaignCounts->get($testCampaigns[0]['id'])->rejected_count, 30);
    }
}
