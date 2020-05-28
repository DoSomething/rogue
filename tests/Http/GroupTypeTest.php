<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\GroupType;

class GroupTypeTest extends TestCase
{
    /**
     * Test that a GET request to /api/v3/group-types returns an index of all group types.
     *
     * GET /api/v3/group-types
     * @return void
     */
    public function testGroupTypeIndex()
    {
        // Create five group types.
        $first = factory(GroupType::class, 5)->create();

        $response = $this->getJson('api/v3/group-types');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(5, $decodedResponse['meta']['pagination']['count']);
    }
}
