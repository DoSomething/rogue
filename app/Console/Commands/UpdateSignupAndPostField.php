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
    protected $signature = 'rogue:updatefield {--target= : The name of the field to update} {--targetOldValue= : The value to search the target with} {--targetNewValue= : The value to update the target with}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change all target old values to target new value in the signups and posts tables';

    /**
     * The signup manager instance.
     *
     * @var Rogue\Managers\SignupManager
     */
    protected $signups;

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
        // Start updating signups
        info('rogue:updatefield: Starting to update signups!');

        if (! $this->option('target')) {
            $this->error('No target field specified.');

            return;
        }

        if (! $this->option('targetOldValue')) {
            $this->error('No target old value specified.');

            return;
        }

        if (! $this->option('targetNewValue')) {
            $this->error('No target new value specified.');

            return;
        }

        $targetField = $this->option('target') ?? null;
        $targetOldValue = $this->option('targetOldValue') && $this->option('targetOldValue') !== 'NULL' ? $this->option('targetOldValue') : null;
        $targetNewValue = $this->option('targetNewValue') && $this->option('targetNewValue') !== 'NULL' ? $this->option('targetNewValue') : null;

        // Get all signups that have "targetOldValue" set as their target and update to "targetNewValue"
        Signup::withTrashed()->where($targetField, $targetOldValue)->chunkById(100, function ($signups) use ($targetField, $targetNewValue) {
            foreach ($signups as $signup) {
                info('rogue:updatefield: changing target field to new value for signup ' . $signup->id);
                $this->signups->update($signup, [$targetField => $targetNewValue]);
            }
        });

        // Log that updating signups are finished
        info('rogue:updatefield: Finished updating signups!');

        // Start updating posts
        info('rogue:updatefield: Starting to update posts!');

        // Get all posts that have "targetOldValue" set as their target and update to "targetNewValue"
        Post::withTrashed()->where($targetField, $targetOldValue)->chunkById(100, function ($posts) use ($targetField, $targetNewValue) {
            foreach ($posts as $post) {
                info('rogue:updatefield: changing target field to new value for post ' . $post->id);
                $this->posts->update($post, [$targetField => $targetNewValue]);
            }
        });

        // Lost that updating posts are finished
        info('rogue:updatefield: Finished updating posts!');
    }
}
