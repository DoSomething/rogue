<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Illuminate\Console\Command;

class MakeDefaultURLsNull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:nullurl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change "default" URLs in the Posts table to null';

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
        info('rogue:nullurl: Starting!');

        // Get all posts with "default" for the url
        $posts = Post::where('url', 'default')->get();

        // Create the progress bar
        $bar = $this->output->createProgressBar($posts->count());

        // Set all of the "default"s to null
        foreach ($posts as $post) {
            info('rogue:nullurl: Nulling url for post ' . $post->id);
            $post->url = null;
            $post->save();
            $bar->advance();
        }

        $bar->finish();
        info('rogue:nullurl: All done!');
    }
}
