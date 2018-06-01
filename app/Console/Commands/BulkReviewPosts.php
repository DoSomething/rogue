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
                            {--type= : Filter by the type of post}
                            {--logfreq=1000}
                            {--log}
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
        info('rogue:bulkreviewposts: Starting to bulk accept posts!');

        $postType = $this->option('type') ?? null;
        $logfreq = $this->option('logfreq');
        $log = $this->option('log');
        $tags = $this->option('tag') ?? null;

        $query = (new Post)->newQuery();
        $query = $query->where('campaign_id', $this->argument('campaign'));
        $query = $query->where('status', $this->argument('oldStatus'));

        if ($postType !== null) {
            $query = $query->where('type', $postType);
        }

        $posts = $query->get();

        if ($posts->isNotEmpty()) {
            foreach ($posts as $post) {
                if ($post->id % $logfreq == 0) {
                    info('rogue:bulkreviewposts: accepting and tagging post: ' . $post->id);
                }

                $this->posts->update($post, ['status' => $this->argument('newStatus')], $log);

                foreach ($tags as $tag) {
                    $tagArray = explode('-', $tag);
                    $tag = implode(' ', $tagArray);
                    $post->tag(ucwords($tag));
                }
            }
        } else {
            $this->error('No posts found with that criteria.');

            return;
        }

        info('rogue:bulkreviewposts: ALL DONE!');
    }
}
