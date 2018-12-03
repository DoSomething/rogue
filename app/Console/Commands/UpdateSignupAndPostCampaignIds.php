<?php

namespace Rogue\Console\Commands;

use DB;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Models\Campaign;
use Illuminate\Console\Command;

class UpdateSignupAndPostCampaignIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:updatesignupandpostcampaignids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates signup and post campaign ids after legacy campaign migration and killing of campaign runs.';

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
        // Grab all of the campaigns in the campaigns table.
        $campaigns = Campaign::all();

        // Update each signup and the signup's post(s) in each campaign with new campaign id.
        foreach ($campaigns as $campaign) {
            dd($campaign->campaign_run_id);
            # code...
        }
    }
}
