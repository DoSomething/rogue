<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Illuminate\Console\Command;
use Rogue\Jobs\SendPostToQuasar;
use Rogue\Jobs\SendSignupToQuasar;

class SendToQuasar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:quasar {start=0000-00-00} {end?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the given items to Quasar.';

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
        info('rogue:quasar - Starting to send to Quasar');

        $start = $this->argument('start');
        $end = $this->argument('end');

        // Send Signups
        $signups = Signup::where('updated_at', '>=', $start);

        if ($end) {
            $signups = $signups->where('updated_at', '<=', $end);
        }

        $signups->chunkById(1000, function ($signups) {
            foreach($signups as $signup) {
                SendSignupToQuasar::dispatch($signup);
            }
        });

        // Send Posts
        $posts = Post::where('updated_at', '>=', $start);

        if ($end) {
            $posts = $posts->where('updated_at', '<=', $end);
        }

        $posts->chunkById(1000, function ($posts) {
            foreach($posts as $post) {
                SendPostToQuasar::dispatch($post);
            }
        });

        info('rogue:quasar - Done sending to Quasar');
    }
}
