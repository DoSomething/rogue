<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Illuminate\Console\Command;
use Rogue\Managers\PostManager;
use Rogue\Managers\SignupManager;

class UpdateSignupAndPostField extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:updatefield {target} {targetOldValue} {targetNewValue} {--signups} {--posts}';

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
        $this->info('rogue:updatefield: Starting script!');

        $targetField = $this->argument('target');
        $targetOldValue = $this->argument('targetOldValue') !== 'NULL' ? $this->argument('targetOldValue') : null;
        $targetNewValue = $this->argument('targetNewValue') !== 'NULL' ? $this->argument('targetNewValue') : null;
        $signups = $this->option('signups');
        $posts = $this->option('posts');

        if ($signups) {
            // Start updating signups
            $this->info('rogue:updatefield: Starting to update signups!');

            // Get all signups that have "targetOldValue" set as their target and update to "targetNewValue"
            Signup::withTrashed()->where($targetField, $targetOldValue)->chunkById(100, function ($signups) use ($targetField, $targetNewValue) {
                foreach ($signups as $signup) {
                    info('rogue:updatefield: changing target field to new value for signup ' . $signup->id);
                    $this->signups->update($signup, [$targetField => $targetNewValue]);
                }
            });

            // Log that updating signups are finished
            $this->info('rogue:updatefield: Finished updating signups!');
        }

        if ($posts) {
            // Start updating posts
            $this->info('rogue:updatefield: Starting to update posts!');

            // Get all posts that have "targetOldValue" set as their target and update to "targetNewValue"
            Post::withTrashed()->where($targetField, $targetOldValue)->chunkById(100, function ($posts) use ($targetField, $targetNewValue) {
                foreach ($posts as $post) {
                    info('rogue:updatefield: changing target field to new value for post ' . $post->id);
                    $this->posts->update($post, [$targetField => $targetNewValue]);
                }
            });

            // Log that updating posts are finished
            $this->info('rogue:updatefield: Finished updating posts!');
        }

        $this->info('rogue:updatefield: ALL DONE!');
    }
}
