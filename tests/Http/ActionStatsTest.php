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

    /**
     * Test that a GET request to /api/v3/action-stats returns an index of all action stats.
     *
     * GET /api/v3/actions
     * @return void
     */
    public function testFilterByGroupTypeId()
    {
        // Create five action stats.
        $actionStats = factory(ActionStat::class, 5)->create();

        $firstGroupType = factory(GroupType::class)->create();
        $firstGroupTypeId = $firstGroupType->id;
        $secondGroupType = factory(GroupType::class)->create();
        $secondGroupTypeId = $secondGroupType->id;

        // Create two groups for our first group type, each with different schools.
        factory(Group::class)->create([
            'group_type_id' => $firstGroupTypeId,
            'school_id' => $actionStats[0]->school_id,
        ]);
        factory(Group::class)->create([
            'group_type_id' => $firstGroupTypeId,
            'school_id' => $actionStats[1]->school_id,
        ]);
        // Create one group for our 2nd group type.
        factory(Group::class)->create([
            'group_type_id' => $secondGroupTypeId,
            'school_id' => $actionStats[0]->school_id,
        ]);

        $response = $this->getJson(
            'api/v3/action-stats?filter[group_type_id]=' . $firstGroupTypeId,
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(2, $decodedResponse['meta']['pagination']['count']);

        $response = $this->getJson(
            'api/v3/action-stats?filter[group_type_id]=' . $secondGroupTypeId,
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(1, $decodedResponse['meta']['pagination']['count']);
    }
}
