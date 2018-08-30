<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Illuminate\Console\Command;
use Rogue\Managers\PostManager;

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
     * The post manager.
     * @var PostManager
     */
    protected $postManager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PostManager $postManager)
    {
        // Post Manager Instance.
        $this->postManager = $postManager;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        info('rogue:forceDeletePosts: Starting to force delete posts by type and source.');

        $posts = Post::where('type', $this->argument('type'))->where('source', $this->argument('source'))->get();

        if ($posts->isNotEmpty()) {
            foreach ($posts as $post) {
                $this->postManager->destroy($post->id);

                $post->forceDelete();
            }

            info('rogue:forceDeletePosts: Posts Deleted!');
        } else {
            info('rogue:forceDeletePosts: No Posts found');
        }
    }
}
