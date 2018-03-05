<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Signup;
use Illuminate\Console\Command;
use Rogue\Services\Three\SignupService;

class UpdateSignup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:updatesignups {--target= : The name of the field to update} {--campaign= : The campaign_id to search for signups under} {--campaign_run= : The campaign_run_id to search for signups under} {--date= : Will be used to search for signups greater than the provided value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update a target field on a set of signups contrained by the provided parameters';

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
        // dd($this->option('target'), $this->option('campaign'), $this->option('campaign_run'), $this->option('date'));
    }
}
