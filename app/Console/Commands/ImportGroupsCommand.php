<?php

namespace Rogue\Console\Commands;

use Exception;
use League\Csv\Reader;
use Rogue\Models\Group;
use Rogue\Models\GroupType;
use Illuminate\Console\Command;

class ImportGroupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:groups-import {groupTypeConfigKey}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates or updates a group type and its groups from a CSV set in config.';

    /**
     * The ID of the group type this command is being run for.
     *
     * @var int
     */
    protected $groupTypeId = null;

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
     * Helper to log info with command name prefix and the group type ID we're importing.
     *
     * @param array $record
     * @param string $key
     * @return string
     */
    private function logInfo($message, $data = [])
    {
        // Append the group type ID to the data if we have it set.
        $data = $this->groupTypeId ? array_merge(['group_type_id' => $this->groupTypeId], $data) : $data;

        info('rogue:groups-import: '.$message, $data ?: []);
    }

    /**
     * Trims the given record value for given key if exists.
     *
     * @param array $record
     * @param string $key
     * @return string
     */
    private function sanitizeValue($record, $key)
    {
        if (! isset($record[$key])) {
            return null;
        }

        return trim($record[$key]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $configKey = 'import.group_types.'.$this->argument('groupTypeConfigKey');
        $config = config($configKey);

        if (! $config) {
            $this->loginfo('Could not find config for key '.$configKey);

            return;
        }

        $this->logInfo('Found group type config', $config);

        // Make a local copy of the CSV.
        $temp = tempnam(sys_get_temp_dir(), 'command_csv');

        file_put_contents($temp, fopen($config['path'], 'r'));

        // Load CSV contents.
        $csv = Reader::createFromPath($temp, 'r');

        $csv->setHeaderOffset(0);

        $numImported = 0;
        $numFailed = 0;
        $numSkipped = 0;

        $groupType = GroupType::firstOrCreate([
            'name' => $config['name'],
            'filter_by_location' => $config['filter_by_location'],
        ]);
        $this->groupTypeId = $groupType->id;

        $this->logInfo('Beginning import');

        foreach ($csv->getRecords() as $record) {
            $groupName = $this->sanitizeValue($record, 'name');
            $groupSchoolId = $this->sanitizeValue($record, 'universal_id');
            $groupState = $this->sanitizeValue($record, 'state');

            if (! $groupName) {
                $numSkipped++;

                $this->logInfo('Skipping row without group name', ['school_id' => $groupSchoolId]);

                continue;
            }

            try {
                $group = Group::firstOrCreate([
                    'group_type_id' => $this->groupTypeId,
                    'name' => $groupName,
                    'city' => $this->sanitizeValue($record, 'city'),
                    'location' => $groupState ? 'US-'.$groupState : null,
                    // Eventually this field will be deprecated, but still used by Group Finder.
                    'state' => $groupState,
                    'school_id' => $groupSchoolId,
                ]);

                $numImported++;

                $this->logInfo('Imported group', ['id' => $group->id, 'name' => $group->name]);
            } catch (Exception $e) {
                $numFailed++;

                $this->logInfo('Error: ' . $e->getMessage(), ['name' => $groupName]);
            }
        }

        return $this->logInfo('Import complete', [
            'imported' => $numImported,
            'skipped' => $numSkipped,
            'failed' => $numFailed,
        ]);
    }
}
