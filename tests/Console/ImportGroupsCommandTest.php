<?php

namespace Tests\Console;

use Tests\TestCase;
use Rogue\Models\GroupType;

class ImportGroupsCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config(['import.group_types.test' => [
            'filter_by_location' => true,
            'name' => 'Automated Test Group Type',
            'path' => 'tests/Console/example-groups.csv',
        ]]);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        config(['import.group_types.test' => null]);
    }

    public function testImportingGroups()
    {
        $this->artisan('rogue:groups-import', ['groupTypeConfigKey' => 'test']);

        $this->assertDatabaseHas('group_types', [
            'name' => 'Automated Test Group Type',
            'filter_by_location' => true,
        ]);

        $groupType = GroupType::where('name', 'Automated Test Group Type')->first();
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
