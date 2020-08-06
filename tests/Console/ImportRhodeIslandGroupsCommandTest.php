<?php

namespace Tests\Console;

use Tests\TestCase;
use Rogue\Models\GroupType;

class ImportRhodeIslandGroupsCommandTest extends TestCase
{
    public function testImportingGroups()
    {
        $this->artisan('rogue:rhode-island-groups-import', ['path' => 'tests/Console/example-rhode-island-groups.csv']);

        $this->assertDatabaseHas('group_types', [
            'name' => 'Rhode Island',
            'filter_by_state' => false,
        ]);

        $groupType = GroupType::where('name', 'Rhode Island')->first();
        $groupTypeId = $groupType->id;

        // Spot check groups were imported successfully.
        $this->assertDatabaseHas('groups', [
            'name' => 'Veazie Street School',
            'group_type_id' => $groupTypeId,
            'city' => 'Providence',
            'location' => 'US-RI',
            'school_id' => '4400192',
        ]);
        $this->assertDatabaseHas('groups', [
            'name' => 'Portsmouth High School',
            'group_type_id' => $groupTypeId,
            'city' => 'Portsmouth',
            'location' => 'US-RI',
            'school_id' => '4400190',
        ]);
        // This row was a duplicate name/city/state with school_id 4400190.
        $this->assertDatabaseMissing('groups', [
            'school_id' => '4400189',
        ]);
        // This row did not have a name column specified, so should not have been imported.
        $this->assertDatabaseMissing('groups', [
            'school_id' => '4400188',
        ]);
    }
}
