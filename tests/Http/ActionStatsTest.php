<?php

namespace Tests\Http;

use App\Models\ActionStat;
use App\Models\Group;
use App\Models\GroupType;
use Tests\TestCase;

class ActionStatsTest extends TestCase
{
    protected $url = 'api/v3/action-stats';

    /**
     * Test that a GET request to /api/v3/action-stats returns an index of all action stats.
     *
     * @return void
     */
    public function testActionStatsIndex()
    {
        // Create five action stats.
        factory(ActionStat::class, 5)->create();

        $response = $this->getJson($this->url);
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
            $this->url . '?filter[group_type_id]=' . $secondGroupTypeId,
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(1, $decodedResponse['meta']['pagination']['count']);

        // Verify no errors are thrown when using a groupBy query.
        $response = $this->getJson(
            $this->url .
                '?orderBy=impact,desc&filter[group_type_id]=' .
                $secondGroupTypeId,
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(1, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test expected results for location filter.
     *
     * @return void
     */
    public function testLocationFilter()
    {
        // Create action stats with different locations.
        $firstActionStat = factory(ActionStat::class)->create([
            'location' => 'US-CA',
        ]);
        $firstSchoolId = $firstActionStat->school_id;
        $secondActionStat = factory(ActionStat::class)->create([
            'location' => 'US-NJ',
        ]);
        $secondSchoolId = $secondActionStat->school_id;
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
            $this->url . '?filter[location]=' . $firstActionStat->location,
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(1, $decodedResponse['meta']['pagination']['count']);

        // Verify no errors are thrown when additionally filtering by group_type_id.
        $response = $this->getJson(
            $this->url .
                '?orderBy=impact,desc&filter[group_type_id]=' .
                $firstGroupTypeId .
                '&filter[location]=' .
                $firstActionStat->location,
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(1, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test expected results for filtering by group ID and after cursor.
     *
     * @return void
     */
    public function testGroupTypeIdFilterAndCursor()
    {
        // Create five action stats.
        $actionStats = factory(ActionStat::class, 5)->create();
        // Create a group with our first action stat's school.
        $group = factory(Group::class)->create([
            'school_id' => $actionStats[0]->school_id,
        ]);

        // Find a cursor to search by.
        $response = $this->getJson($this->url);

        $decodedResponse = $response->decodeResponseJson();
        $firstCursor = $decodedResponse['data'][0]['cursor'];

        // Verify no errors are thrown when additionally filtering by group_type_id.
        $response = $this->getJson(
            $this->url .
                '?filter[group_type_id]=' .
                $group->group_type_id .
                '&cursor[after]=' .
                $firstCursor,
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
    }
}
