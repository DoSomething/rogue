<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Illuminate\Console\Command;
use Rogue\Managers\PostManager;

class BulkReviewPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:bulkreviewposts
                            {campaign}
                            {oldStatus}
                            {newStatus}
                            {type}
                            {--logfreq=1000: Flag to decide how often this command will log when a post is being updated}
                            {--log : Flag to allow logging in Post Manager}
                            {--tag=* : Tag(s) slug to tag post by. e.g. hide-in-gallery}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk review and tag posts constrained by the provided parameters';

    /**
     * The Post Manager instance.
     *
     * @var Rogue\Managers\PostManager
     */
    protected $posts;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PostManager $posts)
    {
        parent::__construct();

        $this->posts = $posts;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        info('rogue:bulkreviewposts: Starting to bulk review posts!');

        $logfreq = $this->option('logfreq');
        $log = $this->option('log');
        $tags = $this->option('tag') ?? null;

        Post::where([
            ['campaign_id', $this->argument('campaign')],
            ['status', $this->argument('oldStatus')],
            ['type', $this->argument('type')]
        ])->chunk(100, function ($posts) {
            if ($posts->isNotEmpty()) {
                foreach ($posts as $post) {
                    if ($post->id % $logfreq == 0) {
                        info('rogue:bulkreviewposts: updating: ' . $post->id);
                    }

                    // If the $log flag is included when the command is run, logging will occur in the Post Manager for each post.
                    $this->posts->update($post, ['status' => $this->argument('newStatus')], $log
                );
                    foreach ($tags as $tag) {
                        $post->tag($tag, $log);
                    }
                }
            } else {
                $this->error('No posts found with that criteria.');

                return;
            }
        });

        info('rogue:bulkreviewposts: ALL DONE!');
    }
}
