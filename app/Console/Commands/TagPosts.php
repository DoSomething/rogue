<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Rogue\Models\Tag;
use Illuminate\Console\Command;

class TagPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:tagposts {post* : The ids of the posts to update, separate by spaces} {--tag=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tag a set of posts with the given tag id';

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
        $postIds = $this->argument('post');

        $posts = Post::find($postIds);
        $tag = Tag::findOrFail($this->option('tag'));

        foreach ($posts as $post)
        {
            $post->tag($tag->tag_name);
        }
    }
}
