<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Illuminate\Console\Command;
use Rogue\Managers\PostManager;

class BulkAcceptPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:bulkacceptposts {campaign} {--type= : Filter by the type of post} {--logfreq=1000} {--log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk approve and tag posts as Bulk & Hide In Gallery constrained by the provided parameters';

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
        info('rogue:bulkacceptposts: Starting to bulk accept posts!');

        $postType = $this->option('type') ?? null;
        $logfreq = $this->option('logfreq');
        $log = $this->option('log');

        $query = (new Post)->newQuery();
        $query = $query->where('campaign_id', $this->argument('campaign'));
        $query = $query->where('status', 'pending');

        if ($postType !== null) {
            $query = $query->where('type', $postType);
        }

        $posts = $query->get();

        if ($posts->isNotEmpty()) {
            foreach ($posts as $post) {
                if ($post->id % $logfreq == 0) {
                    info('rogue:bulkacceptposts: accepting and tagging post: ' . $post->id);
                }

                $this->posts->update($post, ['status' => 'accepted'], $log);
                $post->tag('Hide In Gallery');
                $post->tag('Bulk');
            }
        } else {
            $this->error('No posts found with that criteria.');

            return;
        }

        info('rogue:bulkacceptposts: ALL DONE!');
    }
}
