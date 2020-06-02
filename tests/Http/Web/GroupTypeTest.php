<?php

namespace Tests\Http\Web;

use Tests\TestCase;

class GroupTypeTest extends TestCase
{
    /** @test */
    public function testAdminCanCreateGroupType()
    {
        $name = $this->faker->sentence;

        // Verify redirected to new resource.
        $this->actingAsAdmin()
            ->postJson('group-types', ['name' => $name])
            ->assertRedirect('group-types/1');

        $this->assertDatabaseHas('group_types', [
            'name' => $name,
        ]);

        // Verify cannot duplicate resource name.
        $this->actingAsAdmin()
            ->postJson('group-types', ['name' => $name])
            ->assertStatus(422);
    }

    /** @test */
    public function testStaffCannotCreateGroupType()
    {
        $name = $this->faker->sentence;

        // Verify forbidden error.
        $this->actingAsStaff()
            ->postJson('group-types', ['name' => $name])
            ->assertStatus(403);

        $this->assertDatabaseMissing('group_types', [
            'name' => $name,
        ]);
    }
}
