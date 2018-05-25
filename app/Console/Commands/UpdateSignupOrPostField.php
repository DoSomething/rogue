<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Illuminate\Console\Command;

class UpdateSignupOrPostField extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:updatefield {--target : The name of the field to update} {--targetOldValue= : The value to search the target with} {--targetNewValue= : The value to update the target with}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change all target old values to target new value in the signups and posts tables';

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
        // Start updating signups
        info('rogue:updatefield: Starting to update signups!');

        $targetField = $this->option('target') ?? null;
        $targetOldValue = $this->option('targetOldValue') ?? null;
        $targetNewValue = $this->option('targetNewValue') ?? null;

        if (! $targetField) {
            $this->error('No target field specified.');

            return;
        }

        if (! $targetOldValue) {
            $this->error('No target old value specified.');

            return;
        }

        if (! $targetNewValue) {
            $this->error('No target new value specified.');

            return;
        }

        // Get all signups that have "targetOldValue" set as their target and update to "targetNewValue"
        Signup::withTrashed()->where($targetField, $targetOldValue)->chunkById(100, function ($signups) {
            foreach ($signups as $signup) {
                info('rogue:updatefield: changing target field to new value for signup ' . $signup->id);
                $signup->source = 'sms';
                $signup->save();
            }
        });

        // Log that updating signups are finished
        info('rogue:updatefield: Finished updating signups!');

        // Start updating posts
        info('rogue:updatefield: Starting to update posts!');

        // Get all posts that have "sms-mobilecommons" set as their source and update to "sms"
        Post::withTrashed()->where('source', 'sms-mobilecommons')->chunkById(100, function ($posts) {
            foreach ($posts as $post) {
                info('rogue:updatefield: changing source to sms for post ' . $post->id);
                $post->source = 'sms';
                $post->save();
            }
        });

        // Lost that updating posts are finished
        info('rogue:updatefield: Finished updating posts!');
    }
}
