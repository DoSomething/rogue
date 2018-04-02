<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Signup;
use Illuminate\Console\Command;
use Rogue\Services\PostService;

class ReviewSignup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:reviewsignup {signup} {status} {--admin=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Review all posts under the given signup ID with the given status';

    /**
     * The Post service instance.
     *
     * @var Rogue\Services\PostService
     */
    protected $posts;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PostService $posts)
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
        $signup = Signup::findOrFail($this->argument('signup'));
        $status = $this->argument('status');
        $posts = $signup->posts;
        $admin = $this->option('admin');

        $this->info('Updating status of all posts under signup ID '.$signup->id);
        $this->info('There are ' .$posts->count(). ' posts to update.');

        if ($posts) {
            foreach ($posts as $post) {
                $this->posts->review($post, $status, null, $admin);
            }

            $this->info('All posts under signup ID ' . $signup->id . ' have been updated with a status of ' . $status);
        } else {
            $this->error('There are no posts under signup ID ' . $signup->id);
        }
    }
}
