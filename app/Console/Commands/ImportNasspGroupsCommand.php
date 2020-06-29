<?php

namespace Rogue\Console\Commands;

use Exception;
use League\Csv\Reader;
use Rogue\Models\Group;
use Rogue\Models\GroupType;
use Illuminate\Console\Command;

class ImportNasspGroupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:nassp-groups-import {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create National Association of Secondary School Principals group types and groups from a CSV.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns a trimmed CSV value, or null if equal to the string "NULL".
     *
     * @param string
     * @return string
     */
    public function sanitize($value)
    {
        $result = trim($value);

        return $result == 'NULL' ? null : $result;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Sample CSV path: https://gist.githubusercontent.com/aaronschachter/9cd40a703a43e22077e78e12e011a7de/raw/1d689400d64f461d51dce0e99d2b16334c36b10e/nassp.csv

        $path = $this->argument('path');

        info('rogue:nassp-groups-import: Loading csv from ' . $path);

        // Make a local copy of the CSV.
        $temp = tempnam(sys_get_temp_dir(), 'command_csv');

        file_put_contents($temp, fopen($this->argument('path'), 'r'));

        // Load CSV contents.
        $csv = Reader::createFromPath($temp, 'r');

        $csv->setHeaderOffset(0);

        $numImported = 0;
        $numFailed = 0;
        $numSkipped = 0;

        $nhsGroupType = GroupType::firstOrCreate([
            'name' => 'National Honor Society',
            'filter_by_state' => true,
        ]);
        $njhsGroupType = GroupType::firstOrCreate([
            'name' => 'National Junior Honor Society',
            'filter_by_state' => true,
        ]);
        $nascGroupType = GroupType::firstOrCreate([
            'name' => 'National Student Council',
            'filter_by_state' => true,
        ]);
        $nasspGroupType = GroupType::firstOrCreate([
            'name' => 'National Association of Secondary School Principals',
            'filter_by_state' => true,
        ]);

        info('rogue:nassp-groups-import: Beginning import...');

        /**
         * Without specifying these column names, this command fails with error:
         * "The header record must be an empty or a flat array with unique string value."
         */
        $records = $csv->getRecords(['name', 'NHS', 'NJHS', 'NASC', 'NASSP', 'city', 'state', 'zip', 'universalid', 'ncescode', 'gsid']);

        foreach ($records as $record) {
            $name = trim($record['name']);

            if (trim($record['NHS']) == 'NHS') {
                $groupTypeId = $nhsGroupType->id;
            } elseif (trim($record['NJHS']) == 'NJHS') {
                $groupTypeId = $njhsGroupType->id;
            } elseif (trim($record['NASC']) == 'NASC') {
                $groupTypeId = $nascGroupType->id;
            }

            try {
                $city = $this->sanitize($record['city']);
                $state = $this->sanitize($record['state']);
                $schoolId = $this->sanitize($record['universalid']);

                if ($groupTypeId) {
                    $group = Group::firstOrCreate([
                        'group_type_id' => $groupTypeId,
                        'name' => $name,
                        'city' => $city,
                        'state' => $state,
                        'school_id' => $schoolId,
                    ]);

                    $numImported++;

                    info('rogue:nassp-groups-import: Imported group', ['id' => $group->id, 'name' => $group->name]);
                }

                $nasspGroup = Group::firstOrCreate([
                    'group_type_id' => $nasspGroupType->id,
                    'name' => $name,
                    'city' => $city,
                    'state' => $state,
                    'school_id' => $school_id,
                ]);

                $numImported++;

                info('rogue:nassp-groups-import: Imported group', ['id' => $group->id, 'name' => $group->name]);
            } catch (Exception $e) {
                $numFailed++;

                info('rogue:nassp-groups-import: Error importing group with ' .$name . ':' . $e->getMessage());
            }
        }

        info('rogue:nassp-groups-import: Import completed with ' . $numImported . ' imported, '. $numSkipped .' skipped, and ' . $numFailed . ' failed.');
    }
}
