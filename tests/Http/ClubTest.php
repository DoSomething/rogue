<?php

namespace Tests\Http;

use App\Models\Club;
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
     * Test that we can filter clubs by name.
     * GET /api/v3/campaigns.
     * @return void
     */
    public function testClubIndexNameFilter()
    {
        $clubNames = [
            'Batman Begins',
            'Bipartisan',
            'Brave New World',
            'If I Never Knew You',
            'San Dimas High School',
            'Santa Claus',
        ];

        foreach ($clubNames as $clubName) {
            factory(Club::class)->create([
                'name' => $clubName,
            ]);
        }

        $response = $this->getJson('api/v3/clubs?filter[name]=new');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(2, $decodedResponse['meta']['pagination']['count']);

        $response = $this->getJson('api/v3/clubs?filter[name]=san');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(3, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test that we can paginate clubs using 'after' cursor.
     * GET /api/v3/campaigns.
     * @return void
     */
    public function testClubIndexAfterCursor()
    {
        $clubOne = factory(Club::class)->create();
        $clubTwo = factory(Club::class)->create();

        $response = $this->getJson(
            'api/v3/clubs?cursor[after]=' . $clubOne->getCursor(),
        );
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(1, $decodedResponse['meta']['cursor']['count']);
        $this->assertEquals($clubTwo->id, $decodedResponse['data'][0]['id']);
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
