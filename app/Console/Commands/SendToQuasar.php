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
    protected $signature = 'rogue:quasar
                            {start=0000-00-00 : Include all signups and posts updated on or after this day. Format: YYYY-MM-DD}
                            {end? : Include signups and posts updated up to but not including this day. Format: YYYY-MM-DD}
                            {--logFreq=1 : How often we should log that a Post or Signup has been sent to Quasar. Logging happens every logFreq posts/signups.}';

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
        $logFreq = $this->option('logFreq');

        // Send Signups
        $signups = Signup::where('updated_at', '>=', $start);

        if ($end) {
            $signups = $signups->where('updated_at', '<=', $end);
        }

        $signups->chunkById(1000, function ($signups) use ($logFreq) {
            foreach ($signups as $signup) {
                if ($signup->id % $logFreq === 0) {
                    $log = true;
                } else {
                    $log = false;
                }
                SendSignupToQuasar::dispatch($signup, $log);
            }
        });

        // Send Posts
        $posts = Post::where('updated_at', '>=', $start);

        if ($end) {
            $posts = $posts->where('updated_at', '<=', $end);
        }

        $posts->chunkById(1000, function ($posts) use ($logFreq) {
            foreach ($posts as $post) {
                if ($post->id % $logFreq === 0) {
                    $log = true;
                } else {
                    $log = false;
                }
                SendPostToQuasar::dispatch($post, $log);
            }
        });

        info('rogue:quasar - Done sending to Quasar');
    }
}
