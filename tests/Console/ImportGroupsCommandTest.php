<?php

namespace Tests\Console;

use Tests\TestCase;
use Rogue\Models\GroupType;

class ImportGroupsCommandTest extends TestCase
{
    public function testImportingGroups()
    {
        $name = 'Automated Test Group Type';

        $this->artisan('rogue:groups-import', ['input' => 'tests/Console/example-groups.csv', '--name' => $name]);

        $this->assertDatabaseHas('group_types', [
            'name' => $name,
            'filter_by_location' => false,
        ]);

        $groupType = GroupType::where('name', $name)->first();
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
