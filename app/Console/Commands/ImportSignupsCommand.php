<?php

namespace Rogue\Console\Commands;

use League\Csv\Reader;
use Rogue\Models\Signup;
use Rogue\Services\Registrar;
use Illuminate\Console\Command;

class ImportSignupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:signupimport {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create signups based on the data in the given CSV file.';

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
        // Load the missing signups
        $signups_csv = Reader::createFromPath($this->argument('path'), 'r');
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
                $signup = Signup::create([
                    'northstar_id' => $missing_signup['northstar_id'],
                    'campaign_id' => $missing_signup['campaign_node_id'],
                    'campaign_run_id' => $missing_signup['campaign_run_id'],
                    'source' => 'sms',
                    'created_at' => $missing_signup['signup_created_at_timestamp'],
                ]);

                $this->line('Created signup ' . $signup->id);
            } else {
                $this->line('That signup already exists! Moving on.');
            }

            $this->line('ALL DONE!');
        }
    }
}
