<?php

namespace Rogue\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use Rogue\Services\Registrar;

class ImportPreGambitSignupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:pregambit';

    protected $registrar;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Registrar $registrar)
    {
        parent::__construct();

        $this->registrar = $registrar;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Create array of campaign_ids keyed by campaign_run_id
        $campaigns_csv = Reader::createFromPath('campaign_ids_with_run_ids.csv', 'r');
        $campaigns_csv->setHeaderOffset(0);
        $campaign_ids = $campaigns_csv->getRecords();

        $run_then_campaign = [];

        foreach ($campaign_ids as $record) {
            $run_then_campaign[$record['campaign_run_id']] = $record['campaign_id'];
        }

        // Load the missing signups
        $signups_csv = Reader::createFromPath('all_pre_gambit_sms_signups.csv', 'r');
        $signups_csv->setHeaderOffset(0);
        $missing_signups = $signups_csv->getRecords();

        // Create each missing signup
        foreach ($missing_signups as $missing_signup) {
            // dd($missing_signup);
            // We are given uid, grab Northstar ID from Northstar
            dd($this->registrar->find($missing_signup['uid']));

            $signup = new Signup([
                // 'northstar_id' => ,
                // 'campaign_id' => ,
                'campaign_run_id' => $missing_signup['campaign_run_id'],
                'created_at' => $missing_signup['signup_created_at'],
                // 'updated_at' => NOW
            ]);
            //->with(['timstamps' => false]);
        }
    }
}
