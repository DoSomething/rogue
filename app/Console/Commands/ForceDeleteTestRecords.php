<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Illuminate\Console\Command;

class ForceDeleteTestRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:forceDeleteTestRecords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force deletes signups and posts made in Runscope and Ghost Inspector tests';

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
        // Force delete all signups created by Runscope, identified by source.
        $runscopeSignups = Signup::where('source', 'runscope')->orWhere('source', 'runscope-oauth')->get();
        foreach ($runscopeSignups as $runscopeSignup) {
            $runscopeSignup->forceDelete();
        }

        // Force delete all posts created by Runscope, identified by source.
        $runscopePosts = Post::where('source', 'runscope')->orWhere('source', 'runscope-oauth')->get();
        foreach ($runscopePosts as $runscopePost) {
            $runscopePost->forceDelete();
        }

        $this->info('All Runscope and Ghost Inspector Signups deleted.');

        // Force delete all signups created by Ghost Inspector tests, identified by 'why_participated.'
        $ghostInspectorSignups = Signup::where('why_participated', 'Why! I love to test! Team Bleed for the win! Tongue Cat forever!')->get();
        foreach ($ghostInspectorSignups as $ghostInspectorSignup) {
            $ghostInspectorSignup->forceDelete();
        }

        // Force delete all psots created by Ghost Inspector tests, identified by
        $ghostInspectorPosts = Post::where('text', 'Caption! I love to test! Team Bleed for the win! Tongue Cat forever!')->get();
        foreach ($ghostInspectorPosts as $ghostInspectorPost) {
            $ghostInspectorPost->forceDelete();
        }

        $this->info('All Runscope and Ghost Inspector Posts deleted.');
    }
}
