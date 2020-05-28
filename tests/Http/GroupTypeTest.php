<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\GroupType;
use Illuminate\Database\QueryException;

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
}
