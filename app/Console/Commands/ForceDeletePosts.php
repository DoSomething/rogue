<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Illuminate\Console\Command;

class ForceDeletePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:forceDeletePosts
                            {type}
                            {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force deletes posts in Rogue by type and source';

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
        info('Starting to force delete posts by type and source.');

        Post::where('type', $this->argument('type'))->where('source', $this->argument('source'))->forceDelete();

        info('All posts have been force deleted.');
    }
}
