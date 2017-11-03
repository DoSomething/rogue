<?php

namespace Rogue\Console\Commands;

use Carbon\Carbon;
use League\Csv\Reader;
use Rogue\Models\Signup;

use Rogue\Services\Registrar;
use Illuminate\Console\Command;

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
        // Load the missing signups
        $signups_csv = Reader::createFromPath('all_pre_gambit_sms_signups.csv', 'r');
        $signups_csv->setHeaderOffset(0);
        $missing_signups = $signups_csv->getRecords();

        // Create each missing signup
        foreach ($missing_signups as $missing_signup) {
            // See if the signup exists
            $existing_signup = Signup::where([
                ['northstar_id', $missing_signup['northstar_id']],
                ['campaign_id', $missing_signup['campaign_node_id']],
                ['campaign_run_id', $missing_signup['campaign_run_id']],
            ])->first();

            // Create a signup if there isn't on already
            if (! $existing_signup) {
                $signup = new Signup;
                $signup->northstar_id = $missing_signup['northstar_id'];
                $signup->campaign_id = $missing_signup['campaign_node_id'];
                $signup->campaign_run_id = $missing_signup['campaign_run_id'];
                $signup->source = 'sms';
                $signup->created_at = $missing_signup['signup_created_at_timestamp'];
                $signup->updated_at = Carbon::now();
                $signup->save(['timstamps' => false]);

                $this->line('Created signup ' . $signup->id);
            } else {
                $this->line('That signup already exists! Moving on.');
            }

            $this->line('ALL DONE!');
        }
    }
}
