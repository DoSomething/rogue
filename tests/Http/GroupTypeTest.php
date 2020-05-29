<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\GroupType;

class GroupTypeTest extends TestCase
{
    /**
     * Test that a GET request to /api/v3/group-types returns an index of all group types.
     *
     * @return void
     */
    public function testGroupTypeIndex()
    {
        $groupTypes = factory(GroupType::class, 5)->create();

        $response = $this->getJson('api/v3/group-types');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(5, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test that a GET request to /api/v3/group-types/:id returns the intended group type.
     *
     * @return void
     */
    public function testGroupTypeShow()
    {
        factory(GroupType::class, 5)->create();

        // Create a specific group type to search for.
        $groupType = factory(GroupType::class)->create();

        $response = $this->getJson('api/v3/group-types/' . $groupType->id);
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals($groupType->id, $decodedResponse['data']['id']);
    }
}
