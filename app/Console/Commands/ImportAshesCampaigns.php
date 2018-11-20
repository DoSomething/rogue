<?php

namespace Rogue\Console\Commands;

use Carbon\Carbon;
use League\Csv\Reader;
use Rogue\Models\Campaign;
use Rogue\Services\Registrar;
use Illuminate\Console\Command;

class ImportAshesCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:ashescampaignsimport {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports legacy campaigns from Ashes based on the data in the given (link to a) CSV file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Registrar $registrar)
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
        // Make a local copy of the CSV
        $path = $this->argument('path');
        info('rogue:legacycampaignimport: Loading in csv from ' . $path);

        $temp = tempnam(sys_get_temp_dir(), 'command_csv');
        file_put_contents($temp, fopen($this->argument('path'), 'r'));

        // Load the legacy campaigns from the CSV
        $legacy_campaigns_csv = Reader::createFromPath($temp, 'r');
        $legacy_campaigns_csv->setHeaderOffset(0);
        $legacy_campaigns = $legacy_campaigns_csv->getRecords();

        // Import each legacy campaign
        info('rogue:legacycampaignimport: Importing legacy campaigns...');

        foreach ($legacy_campaigns as $legacy_campaign) {
            // Normalize all "NULL" values to null
            foreach ($legacy_campaign as $key => $value) {
                if ($value === "NULL") {
                    $legacy_campaign[$key] = null;
                }
            }

            // See if the campaign exists
            $existing_campaign = Campaign::where('id', $legacy_campaign['campaign_id'])->orWhere('id', $legacy_campaign['run_id'])->first();

            // Create the campaign if there isn't one already
            if (! $existing_campaign) {
                // If there is no campaign_id, set the campaign_run_id to the id
                if (is_null($legacy_campaign['campaign_id'])) {
                    $campaign = Campaign::create([
                        'id' => $legacy_campaign['run_id'],
                        'internal_title' => $legacy_campaign['internal_title'],
                        'cause' => $legacy_campaign['cause'],
                        'secondary_causes' => $legacy_campaign['secondary_causes'],
                        'campaign_run_id' => null,
                        'start_date' => $legacy_campaign['start_date'],
                        'end_date' => $legacy_campaign['end_date'],
                        'created_at' => $legacy_campaign['created_at'],
                        'updated_at' => $legacy_campaign['updated_at'],
                    ]);
                }
            }
        }
    }
}
