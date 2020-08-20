<?php

namespace Tests\Console;

use Tests\TestCase;
use Rogue\Models\Group;
use Rogue\Models\GroupType;

class ImportNasspGroupsCommandTest extends TestCase
{
  public function testImportingGroups()
  {
    $this->artisan('rogue:nassp-groups-import', [
      'path' => 'tests/Console/example-nassp-groups.csv',
    ]);

    // Four group types should have been created.
    $this->assertTrue(GroupType::all()->count() === 4);

    $nasspGroupType = GroupType::where(
      'name',
      'National Association of Secondary School Principals'
    )->first();
    $nasspGroupTypeId = $nasspGroupType->id;

    // We should have 7 NASSP groups per unique name/city/state rows found in file.
    $this->assertTrue(
      Group::where('group_type_id', $nasspGroupTypeId)->count() === 7
    );

    // Get NASC Group Type ID.
    $nascGroupType = GroupType::where(
      'name',
      'National Student Council'
    )->first();
    $nascGroupTypeId = $nascGroupType->id;

    $this->assertTrue(
      Group::where('group_type_id', $nascGroupTypeId)->count() === 1
    );

    // Get NHS Group Type ID.
    $nhsGroupType = GroupType::where('name', 'National Honor Society')->first();
    $nhsGroupTypeId = $nhsGroupType->id;

    $this->assertTrue(
      Group::where('group_type_id', $nhsGroupTypeId)->count() === 4
    );

    // Get NJHS Group Type ID.
    $njhsGroupType = GroupType::where(
      'name',
      'National Junior Honor Society'
    )->first();
    $njhsGroupTypeId = $njhsGroupType->id;

    $this->assertTrue(
      Group::where('group_type_id', $njhsGroupTypeId)->count() === 2
    );

    // Spot check Cobre High School was imported successfully.
    $groupName = 'Cobre High School';

    $this->assertDatabaseHas('groups', [
      'name' => $groupName,
      'group_type_id' => $nhsGroupTypeId,
      'city' => 'Bayard',
      'location' => 'US-NM',
      'school_id' => '3500277',
    ]);
    $this->assertDatabaseHas('groups', [
      'name' => $groupName,
      'group_type_id' => $nasspGroupTypeId,
      'city' => 'Bayard',
      'location' => 'US-NM',
      'school_id' => '3500277',
    ]);
    $this->assertDatabaseMissing('groups', [
      'name' => $groupName,
      'group_type_id' => $njhsGroupTypeId,
    ]);
    $this->assertDatabaseMissing('groups', [
      'name' => $groupName,
      'group_type_id' => $nascGroupTypeId,
    ]);

    // Spot check Scio Central School was imported for NASSP, NHS, NJHS.
    $groupName = 'Scio Central School';

    $this->assertDatabaseHas('groups', [
      'name' => $groupName,
      'group_type_id' => $nasspGroupTypeId,
      'city' => 'Scio',
      'location' => 'US-NY',
      'school_id' => '3603585',
    ]);
    $this->assertDatabaseHas('groups', [
      'name' => $groupName,
      'group_type_id' => $nhsGroupTypeId,
      'city' => 'Scio',
      'location' => 'US-NY',
      'school_id' => '3603585',
    ]);
    $this->assertDatabaseHas('groups', [
      'name' => $groupName,
      'group_type_id' => $njhsGroupTypeId,
      'city' => 'Scio',
      'location' => 'US-NY',
      'school_id' => '3603585',
    ]);
    $this->assertDatabaseMissing('groups', [
      'name' => $groupName,
      'group_type_id' => $nascGroupTypeId,
    ]);

    // Spot check Donald Duck was not imported for any groups besides NASSP.
    $groupName = 'Donald Duck Charter High School';

    $this->assertDatabaseHas('groups', [
      'name' => $groupName,
      'group_type_id' => $nasspGroupTypeId,
    ]);
    $this->assertDatabaseMissing('groups', [
      'name' => $groupName,
      'group_type_id' => $nhsGroupTypeId,
    ]);
    $this->assertDatabaseMissing('groups', [
      'name' => $groupName,
      'group_type_id' => $njhsGroupTypeId,
    ]);
    $this->assertDatabaseMissing('groups', [
      'name' => $groupName,
      'group_type_id' => $nascGroupTypeId,
    ]);
  }
}
