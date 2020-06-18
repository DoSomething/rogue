<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Group;
use Rogue\Models\GroupType;

class GroupTest extends TestCase
{
    /**
     * Test that a GET request to /api/v3/groups returns an index of all group types.
     *
     * @return void
     */
    public function testGroupTypeIndex()
    {
        $groupType = factory(GroupType::class)->create();
        $groupTypeId = $groupType->id;

        factory(Group::class)->create([
            'group_type_id' => $groupTypeId,
            'name' => 'Chicago',
        ]);
        factory(Group::class)->create([
            'group_type_id' => $groupTypeId,
            'name' => 'New Jersey',
        ]);
        factory(Group::class)->create([
            'group_type_id' => $groupTypeId,
            'name' => 'New York',
        ]);
        factory(Group::class)->create([
            'group_type_id' => $groupTypeId,
            'name' => 'Newfoundland',
        ]);
        factory(Group::class)->create([
            'group_type_id' => $groupTypeId,
            'name' => 'Philadelphia',
        ]);
        factory(Group::class)->create([
            'group_type_id' => $groupTypeId,
            'name' => 'San Diego',
        ]);
        factory(Group::class)->create([
            'group_type_id' => $groupTypeId,
            'name' => 'San Francisco',
        ]);
        factory(Group::class)->create([
            'group_type_id' => $groupTypeId,
            'name' => 'Sangria',
        ]);

        $response = $this->getJson('api/v3/groups');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(8, $decodedResponse['meta']['pagination']['count']);

        $response = $this->getJson('api/v3/groups?filter[name]=new');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(3, $decodedResponse['meta']['pagination']['count']);

        $response = $this->getJson('api/v3/groups?filter[name]=new');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(3, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test that a GET request to /api/v3/groups/:id returns the intended group type.
     *
     * @return void
     */
    public function testGroupShow()
    {
        factory(Group::class, 5)->create();

        // Create a specific group type to search for.
        $group = factory(Group::class)->create();

        $response = $this->getJson('api/v3/groups/' . $group->id);
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals($group->id, $decodedResponse['data']['id']);
    }
}
