<?php

namespace Tests\Http\Web;

use Tests\TestCase;
use Rogue\Models\GroupType;

class GroupTypeTest extends TestCase
{
    /** @test */
    public function testAdminCanCreateGroupType()
    {
        $name = $this->faker->sentence;

        // Verify redirected to new resource.
        $this->actingAsAdmin()
            ->postJson('group-types', ['name' => $name, 'filter_by_state' => true])
            ->assertStatus(302);

        $this->assertDatabaseHas('group_types', [
            'name' => $name,
            'filter_by_state' => 1,
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

    /** @test */
    public function testUnsettingFilterByState()
    {
        $groupType = factory(GroupType::class)->create(['filter_by_state' => true]);

        // Verify redirected upon success.
        $this->actingAsAdmin()
            ->putJson('group-types/'.$groupType->id, ['name' => 'Test 123'])
            ->assertStatus(302);

        $this->assertDatabaseHas('group_types', [
            'id' => $groupType->id,
            'filter_by_state' => 0,
        ]);
    }
}
