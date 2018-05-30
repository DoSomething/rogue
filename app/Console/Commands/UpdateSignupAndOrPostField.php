<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Illuminate\Console\Command;
use Rogue\Managers\PostManager;
use Rogue\Managers\SignupManager;

class UpdateSignupAndOrPostField extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:updatefield {target} {targetOldValue} {targetNewValue} {--signups} {--posts} {--logfreq=10000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change all target old values to target new value in the signups and/or posts tables';

    /**
     * The Signup Manager instance.
     *
     * @var Rogue\Managers\SignupManager
     */
    protected $signups;

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
    public function __construct(SignupManager $signups, PostManager $posts)
    {
        parent::__construct();

        $this->signups = $signups;
        $this->posts = $posts;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        info('rogue:updatefield: Starting script!');

        $targetField = $this->argument('target');
        $targetOldValue = $this->argument('targetOldValue') !== 'NULL' ? $this->argument('targetOldValue') : null;
        $targetNewValue = $this->argument('targetNewValue') !== 'NULL' ? $this->argument('targetNewValue') : null;
        $signups = $this->option('signups');
        $posts = $this->option('posts');
        $logfreq = $this->option('logfreq');

        if ($signups) {
            // Start updating signups
            info('rogue:updatefield: Starting to update signups!');

            // Get all signups that have "targetOldValue" set as their target and update to "targetNewValue"
            Signup::withTrashed()->where($targetField, $targetOldValue)->chunkById(100, function ($signups) use ($targetField, $targetNewValue, $logfreq) {
                foreach ($signups as $signup) {
                    if ($signup->id % $logfreq == 0) {
                        info('rogue:updatefield: changing ' . $targetField . ' to ' . $targetNewValue . ' for signup ' . $signup->id);
                    }

                    // Update signup but don't log that it was sent to Quasar in interest of taking up too much space in Papertrail.
                    // @TODO: when running updates for smaller amount of records, do we want to turn logs back on?
                    $this->signups->update($signup, [$targetField => $targetNewValue], false);
                }
            });

            // Log that updating signups are finished
            info('rogue:updatefield: Finished updating signups!');
        }

        if ($posts) {
            // Start updating posts
            info('rogue:updatefield: Starting to update posts!');

            // Get all posts that have "targetOldValue" set as their target and update to "targetNewValue"
            Post::withTrashed()->where($targetField, $targetOldValue)->chunkById(100, function ($posts) use ($targetField, $targetNewValue, $logfreq) {
                foreach ($posts as $post) {
                    if ($post->id % $logfreq == 0) {
                        info('rogue:updatefield: changing ' . $targetField . ' to ' . $targetNewValue . ' for post ' . $post->id);
                    }

                    // Update post but don't log that it was sent to Quasar in interest of taking up too much space in Papertrail.
                    // @TODO: when running updates for smaller amount of records, do we want to turn logs back on?
                    $this->posts->update($post, [$targetField => $targetNewValue], false);
                }
            });

            // Log that updating posts are finished
            info('rogue:updatefield: Finished updating posts!');
        }

        info('rogue:updatefield: ALL DONE!');
    }
}
