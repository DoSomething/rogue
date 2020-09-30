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
     * Test expected results for group_type_id filter.
     *
     * @return void
     */
    public function testGroupTypeIdFilter()
    {
        // Create five action stats.
        $actionStats = factory(ActionStat::class, 5)->create();
        $firstSchoolId = $actionStats[0]->school_id;
        $secondSchoolId = $actionStats[1]->school_id;
        $firstGroupType = factory(GroupType::class)->create();
        $firstGroupTypeId = $firstGroupType->id;
        $secondGroupType = factory(GroupType::class)->create();
        $secondGroupTypeId = $secondGroupType->id;

        // Create two groups for our first group type, each with different schools.
        factory(Group::class)->create([
            'group_type_id' => $firstGroupTypeId,
            'school_id' => $firstSchoolId,
        ]);
        factory(Group::class)->create([
            'group_type_id' => $firstGroupTypeId,
            'school_id' => $secondSchoolId,
        ]);
        // Create one group for our 2nd group type.
        factory(Group::class)->create([
            'group_type_id' => $secondGroupTypeId,
            'school_id' => $firstSchoolId,
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

        // Verify no errors are thrown when using a groupBy query.
        $response = $this->getJson(
            'api/v3/action-stats?orderBy=impact,desc&filter[group_type_id]=' .
                $secondGroupTypeId,
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(1, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test expected results for exclude filter without exclude_by_field.
     *
     * @return void
     */
    public function testExcludeFilter()
    {
        $actionStats = factory(ActionStat::class, 5)->create();

        // Test expected results using exclude and exclude_by_field filters.
        $response = $this->getJson(
            'api/v3/action-stats?filter[exclude]=' . $actionStats[3]->id,
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(4, $decodedResponse['meta']['pagination']['count']);
        $response->assertJson([
            'data' => [
                ['id' => $actionStats[0]->id],
                ['id' => $actionStats[1]->id],
                ['id' => $actionStats[2]->id],
                ['id' => $actionStats[4]->id],
            ],
        ]);
    }

    /**
     * Test expected results for exclude_by_field filter.
     *
     * @return void
     */
    public function testExcludeByFieldFilter()
    {
        $actionStats = factory(ActionStat::class, 5)->create();

        // Test expected results using exclude and exclude_by_field filters.
        $response = $this->getJson(
            'api/v3/action-stats?filter[exclude]=' .
                $actionStats[0]->school_id .
                '&filter[exclude_by_field]=school_id',
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(4, $decodedResponse['meta']['pagination']['count']);
        $response->assertJson([
            'data' => [
                ['id' => $actionStats[1]->id],
                ['id' => $actionStats[2]->id],
                ['id' => $actionStats[3]->id],
                ['id' => $actionStats[4]->id],
            ],
        ]);
    }
}
