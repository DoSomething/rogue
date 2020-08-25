<?php

namespace Tests\Http\Web;

use Rogue\Models\Club;
use Tests\TestCase;

class ClubTest extends TestCase
{
    /**
     * Test that admin can create a new club.
     *
     * POST /clubs
     * @return void
     */
    public function testAdminCanCreateClub()
    {
        $name = $this->faker->sentence;
        $leaderId = $this->faker->unique()->northstar_id;

        // Verify redirected to new resource.
        $this->actingAsAdmin()
            ->postJson('clubs', ['name' => $name, 'leader_id' => $leaderId])
            ->assertStatus(302);

        // Make sure the club is persisted in the database.
        $this->assertDatabaseHas('clubs', [
            'name' => $name,
            'leader_id' => $leaderId,
        ]);
    }

    /**
     * Test that staff can create a new club.
     *
     * POST /clubs
     * @return void
     */
    public function testStaffCanCreateClub()
    {
        $name = $this->faker->sentence;
        $leaderId = $this->faker->unique()->northstar_id;

        // Verify redirected to new resource.
        $this->actingAsStaff()
            ->postJson('clubs', ['name' => $name, 'leader_id' => $leaderId])
            ->assertStatus(302);

        // Make sure the club is persisted in the database.
        $this->assertDatabaseHas('clubs', [
            'name' => $name,
            'leader_id' => $leaderId,
        ]);
    }

    /**
     * Test validation for creating a club.
     *
     * POST /clubs
     * @return void
     */
    public function testCreatingAClubWithValidationErrors()
    {
        $this->actingAsAdmin()
            ->postJson('clubs', [
                'name' => 123, // This should be a string.
                'leader_id' => 'Maddy is the leader!', // This should be a MongoDB ObjectID.
                'location' => 'wakanda', // This should be an iso3166 string.
                'city' => 789, // This should be a string.
                'school_id' => 101112, // This should be a string.
            ])
            ->assertJsonValidationErrors([
                'name',
                'leader_id',
                'location',
                'city',
                'school_id',
            ]);
    }

    /**
     * test validation for creating a club with a duplicate leader_id.
     *
     * POST /clubs
     * @return void
     */
    public function testCreatingAClubWithDuplicateLeaderId()
    {
        $club = factory(Club::class)->create();

        $this->actingAsAdmin()
            ->postJson('clubs', [
                'name' => $this->faker->company,
                'leader_id' => $club->leaderId,
            ])
            ->assertJsonValidationErrors(['leader_id']);
    }

    /**
     * Test for updating a club successfully.
     *
     * PATCH /clubs/:id
     * @return void
     */
    public function testUpdatingAClub()
    {
        $club = factory(Club::class)->create();

        $name = $this->faker->company;
        $leaderId = $this->faker->unique()->northstar_id;
        $location = 'US-' . $this->faker->stateAbbr;
        $city = $this->faker->city;
        $schooolId = $this->faker->school->school_id;

        $response = $this->actingAsAdmin()->patchJson('clubs/' . $club->id, [
            'name' => $name,
            'leader_id' => $leaderId,
            'location' => $location,
            'city' => $city,
            'school_id' => $schooolId,
        ]);

        $response->assertStatus(302);

        // Make sure that the club's updated attributes are persisted in the database.
        $this->assertEquals($club->fresh()->name, $name);
        $this->assertEquals($club->fresh()->leader_id, $leaderId);
        $this->assertEquals($club->fresh()->location, $location);
        $this->assertEquals($club->fresh()->city, $city);
        $this->assertEquals($club->fresh()->school_id, $schooolId);
    }

    /**
     * Test for updating a club without changing the leader_id successfully.
     *
     * PATCH /clubs/:id
     * @return void
     */
    public function testUpdatingAClubWithoutChangingTheLeaderId()
    {
        $club = factory(Club::class)->create();

        $name = $this->faker->company;

        $response = $this->actingAsAdmin()->patchJson('clubs/' . $club->id, [
            'name' => $name,
            'leader_id' => $club->leader_id,
        ]);

        $response->assertStatus(302);

        // Make sure that the club's updated attributes are persisted in the database.
        $this->assertEquals($club->fresh()->name, $name);
        $this->assertEquals($club->fresh()->leader_id, $club->leader_id);
    }

    /**
     * test validation for updating a club.
     *
     * PATCH /clubs/:id
     * @return void
     */
    public function testUpdatingAClubWithValidationErrors()
    {
        $club = factory(Club::class)->create();

        $this->actingAsAdmin()
            ->patchJson('clubs/' . $club->id, [
                'name' => 123, // This should be a string.
                'leader_id' => 'Maddy is the leader!', // This should be a MongoDB ObjectID.
                'location' => 'wakanda', // This should be an iso3166 string.
                'city' => 789, // This should be a string.
                'school_id' => 101112, // This should be a string.
            ])
            ->assertJsonValidationErrors([
                'name',
                'leader_id',
                'location',
                'city',
                'school_id',
            ]);
    }
}
