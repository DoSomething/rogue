<?php

namespace Tests\Http\Web;

use Tests\TestCase;
use Rogue\Models\GroupType;

class GroupTest extends TestCase
{
    /** @test */
    public function testAdminCanCreateGroup()
    {
        $groupType = factory(GroupType::class)->create();
        $name = $this->faker->sentence;

        // Verify redirected to new resource.
        $this->actingAsAdmin()
            ->postJson('groups', [
                'group_type_id' => $groupType->id,
                'name' => $name,
            ])
            ->assertStatus(302);

        $this->assertDatabaseHas('groups', [
            'group_type_id' => $groupType->id,
            'name' => $name,
        ]);

        // Verify cannot duplicate resource name + group_type_id.
        $this->actingAsAdmin()
            ->postJson('groups', [
                'group_type_id' => $groupType->id,
                'name' => $name,
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function testStaffCanCreateGroup()
    {
        $groupType = factory(GroupType::class)->create();
        $name = $this->faker->sentence;

        // Verify redirected to new resource.
        $this->actingAsStaff()
            ->postJson('groups', [
                'group_type_id' => $groupType->id,
                'name' => $name,
            ])
            ->assertStatus(302);
    }
}
