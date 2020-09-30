<?php

namespace Tests\Http;

use Rogue\Models\ActionStat;
use Rogue\Models\Group;
use Rogue\Models\GroupType;
use Tests\TestCase;

class ActionStatsTest extends TestCase
{
    /**
     * Test that a GET request to /api/v3/action-stats returns an index of all action stats.
     *
     * GET /api/v3/actions
     * @return void
     */
    public function testActionStatsIndex()
    {
        // Create five action stats.
        factory(ActionStat::class, 5)->create();

        $response = $this->getJson('api/v3/action-stats');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(5, $decodedResponse['meta']['pagination']['count']);
    }
}
