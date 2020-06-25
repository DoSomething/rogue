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
     * @param string
     * @return string
     */
    public function sanitize($value)
    {
        $result = trim($value);

        return $result == 'NULL' ? null : $value;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
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
        ]);
        $njhsGroupType = GroupType::firstOrCreate([
            'name' => 'National Junior Honor Society',
        ]);
        $nascGroupType = GroupType::firstOrCreate([
            'name' => 'National Student Council',
        ]);

        info('rogue:nassp-groups-import: Beginning import...');

        /**
         * Without specifying these column names, this command fails with error:
         * "The header record must be an empty or a flat array with unique string value."
         */
        $records = $csv->getRecords(['LABEL NAME', 'NHS', 'NJHS', 'NASC', 'NASSP', 'ADDRESS1', 'ADDRESS2', 'CITY', 'STATE', 'POSTAL', 'COUNTRY', 'NCES']);

        foreach ($records as $record) {
            $name = trim($record['LABEL NAME']);

            if (trim($record['NHS']) == 'NHS') {
                $groupTypeId = $nhsGroupType->id;
            } elseif (trim($record['NJHS']) == 'NJHS') {
                $groupTypeId = $njhsGroupType->id;
            } elseif (trim($record['NASC']) == 'NASC') {
                $groupTypeId = $nascGroupType->id;
            } else {
                $numSkipped++;

                info('rogue:nassp-groups-import: Skipped - no group type match for ' .$name . '.');

                continue;
            }

            try {
                $group = Group::firstOrCreate([
                    'group_type_id' => $groupTypeId,
                    'name' => $name,
                    'city' => $this->sanitize($record['CITY']),
                    'state' => $this->sanitize($record['STATE']),
                    'external_id' => $this->sanitize($record['NCES']),
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
