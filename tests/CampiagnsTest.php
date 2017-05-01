<?php

use Rogue\Services\CampaignService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CampiagnsTest extends TestCase
{
    /**
     * A basic test example.
     *s
     * @return void
     */
    public function testFindingASingleCampaignInCache()
    {
        Cache::shouldReceive('get')->andReturn(true);

        $campaignService = new CampaignService();

        $campaignService->find('1234');
    }
}
