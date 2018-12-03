<?php

namespace Rogue\Console\Commands;

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
    protected $signature = 'rogue:updatesignupandpostcampaignids {--campaign= : The campaign_id to update signup and posts under}';

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
        $this->line('rogue:updatesignupandpostcampaignids: Starting script to update campaign ids!');


        // Grab all of the campaigns in the campaigns table.
        $query = (new Campaign)->newQuery();

        if ($this->option('campaign')) {
            $query = $query->where('id', $this->option('campaign'));
        }

        $campaigns = $query->get();

        // Update each signup and the signup's post(s) in each campaign with new campaign id.
        foreach ($campaigns as $campaign) {
            $signups = Signup::where('campaign_run_id', $campaign->campaign_run_id)->get();

            foreach ($signups as $signup) {
                // Update the signup's campaign id to the $campaign->id
                $signup->campaign_id = $campaign->id;
                $signup->save();

                // Update all the posts' campaign ids that are associated with this signup.
                $posts = Post::where('signup_id', $signup->id)->get();

                foreach ($posts as $post) {
                    $post->campaign_id = $campaign->id;
                    $post->save();
                }
            }
        }

        // Tell everyone we're done!
        $this->line('rogue:updatesignupandpostcampaignids: ALL DONE!');
    }
}
