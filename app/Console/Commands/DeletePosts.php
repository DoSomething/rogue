<?php

namespace Rogue\Console\Commands;

use Illuminate\Console\Command;
use Rogue\Models\Post;

class DeletePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:deleteposts
                            {ids* : A space-separated list of post ids to delete}
                            {--force : Whether or not to force delete these posts instead of soft delete (default)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete posts in Rogue by post id with an option to force delete';

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
        $ids = $this->argument('ids');

        $posts = Post::whereIn('id', $ids);

        if ($this->option('force')) {
            $posts->forceDelete();
        } else {
            $posts->delete();
        }

        $this->info('Posts Deleted!');
    }
}
