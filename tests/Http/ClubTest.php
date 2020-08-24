<?php

namespace Tests\Http;

use Rogue\Models\Club;
use Tests\TestCase;

class ClubTest extends TestCase
{
    /**
     * Test that a GET request to /api/v3/clubs returns an index of all clubs.
     *
     * @return void
     */
    public function testClubIndex()
    {
        factory(Club::class, 5)->create();

        $response = $this->getJson('api/v3/clubs');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(5, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test that a GET request to /api/v3/clubs/:id returns the intended club.
     *
     * @return void
     */
    public function testClubShow()
    {
        factory(Club::class, 5)->create();

        // Create a specific club to search for.
        $club = factory(Club::class)->create();

        $response = $this->getJson('api/v3/clubs/' . $club->id);
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals($club->id, $decodedResponse['data']['id']);
    }
}
