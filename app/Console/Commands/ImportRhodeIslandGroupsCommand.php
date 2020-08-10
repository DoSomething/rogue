<?php

namespace Rogue\Console\Commands;

use Exception;
use League\Csv\Reader;
use Rogue\Models\Group;
use Rogue\Models\GroupType;
use Illuminate\Console\Command;

class ImportRhodeIslandGroupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:rhode-island-groups-import {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Rhode Island Department of Education groups from a CSV.';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Example path: https://gist.githubusercontent.com/aaronschachter/79d0d78289d4d9ee09287155d84a5d39/raw/ba602f249e1562a2b04b206dcb0da0ba83fd5dd5/groups.csv

        $path = $this->argument('path');

        info('rogue:rhode-island-groups-import: Loading csv from ' . $path);

        // Make a local copy of the CSV.
        $temp = tempnam(sys_get_temp_dir(), 'command_csv');

        file_put_contents($temp, fopen($this->argument('path'), 'r'));

        // Load CSV contents.
        $csv = Reader::createFromPath($temp, 'r');

        $csv->setHeaderOffset(0);

        $numImported = 0;
        $numFailed = 0;
        $numSkipped = 0;

        $groupType = GroupType::firstOrCreate([
            // This name is user facing (which is why we're not naming it "Rhode Island Dept of Education").
            'name' => 'Rhode Island',
        ]);
        $groupTypeId = $groupType->id;

        info('rogue:rhode-island-groups-import: Beginning import for group type id ' . $groupTypeId .'.');

        foreach ($csv->getRecords() as $record) {
            $name = trim($record['name']);
            $schoolId = trim($record['universalid']);

            if (! $name) {
                info('rogue:rhode-island-groups-import: Skipping row without name', ['school_id' => $schoolId]);

                $numSkipped++;

                continue;
            }

            try {
                $group = Group::firstOrCreate([
                    'group_type_id' => $groupTypeId,
                    'name' => $name,
                    'city' => trim($record['city']),
                    'location' => 'US-'.trim($record['state']),
                    'school_id' => $schoolId,
                ]);

                $numImported++;

                info('rogue:rhode-island-groups-import: Imported group', ['id' => $group->id, 'name' => $group->name]);
            } catch (Exception $e) {
                $numFailed++;

                info('rogue:rhode-island-groups-import: Error importing group with ' .$name . ':' . $e->getMessage());
            }
        }

        info('rogue:rhode-island-import: Import completed with ' . $numImported . ' imported, ' . $numSkipped . ' skipped, and ' . $numFailed . ' failed.');
    }
}
