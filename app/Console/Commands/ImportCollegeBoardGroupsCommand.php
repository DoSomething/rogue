<?php

namespace Rogue\Console\Commands;

use Exception;
use League\Csv\Reader;
use Rogue\Models\Group;
use Rogue\Models\GroupType;
use Illuminate\Console\Command;

class ImportCollegeBoardGroupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:college-board-groups-import {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create groups for The College Board from a CSV.';

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
        // Example path: https://gist.githubusercontent.com/aaronschachter/2933b5d29bbee4a866de2de49a1f35f7/raw/452d267228d2478de6e845982291c7510cab08cf/groups.csv 

        $path = $this->argument('path');

        info('rogue:college-board-groups-import: Loading csv from ' . $path);

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
            'name' => 'The College Board',
        ]);
        $groupTypeId = $groupType->id;

        info('rogue:college-board-groups-import: Beginning import for group type id ' . $groupTypeId .'.');

        foreach ($csv->getRecords() as $record) {
            $name = trim($record['name']);
            $schoolId = trim($record['universal_id']);

            if (! $name) {
                $numSkipped++;

                info('rogue:college-board-groups-import: Skipping row without name', ['school_id' => $schoolId]);

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

                info('rogue:rcollege-board-groups-import: Imported group', ['id' => $group->id, 'name' => $group->name]);
            } catch (Exception $e) {
                $numFailed++;

                info('rogue:college-board-groups-import: Error importing group with ' .$name . ':' . $e->getMessage());
            }
        }

        info('rogue:college-board-import: Import completed with ' . $numImported . ' imported, ' . $numSkipped . ' skipped, and ' . $numFailed . ' failed.');
    }
}
