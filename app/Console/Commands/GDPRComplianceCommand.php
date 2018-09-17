<?php

namespace Rogue\Console\Commands;

use League\Csv\Reader;
use Rogue\Models\Post;
use Rogue\Services\AWS;
use Rogue\Models\Signup;
use Rogue\Services\Fastly;
use Illuminate\Console\Command;
use Rogue\Jobs\SendPostToQuasar;
use Rogue\Jobs\SendSignupToQuasar;
use Rogue\Jobs\SendPostToCustomerIo;
use Rogue\Jobs\SendSignupToCustomerIo;

class GDPRComplianceCommand extends Command
{
    /**
     * The Fastly service instance
     *
     * @var Rogue\Services\Fastly
     */
    protected $fastly;

    /**
     * AWS service class instance.
     *
     * @var \Rogue\Services\AWS
     */
    protected $aws;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:gdpr {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anonymizes data for users given in CSV in compliance with GDPR. Specifically, annonymizes caption and why_participated, deletes image URLs from s3 and tags all given users\' posts as hide-in-gallery.';

    /**
     * Create a new command instance.
     *
     * @param AWS $aws
     * @return void
     */
    public function __construct(AWS $aws, Fastly $fastly)
    {
        parent::__construct();
        $this->aws = $aws;
        $this->fastly = $fastly;
    }

    /**
     * Executre the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Make a local copy of the CSV
        $path = $this->argument('path');
        $this->info('rogue:gdpr: Loading in csv from ' . $path);

        $temp = tempnam(sys_get_temp_dir(), 'command_csv');
        file_put_contents($temp, fopen($this->argument('path'), 'r'));

        // Load users from the CSV
        $users_csv = Reader::createFromPath($temp, 'r');
        $users_csv->setHeaderOffset(0);
        $users = $users_csv->getRecords();

        // Anonymize user data.
        $this->info('rogue:gdpr: Anonymizing data...');
        foreach ($users as $user) {
            // Find all the user's signups and anonymize all why_participated values.
            $signups = Signup::withTrashed()->where('northstar_id', $user['Users Northstar ID'])->get();

            foreach ($signups as $signup) {
                $signup->why_participated = 'EU Member. Removed because of GDPR';
                $signup->save();

                // Business Logic
                SendSignupToQuasar::dispatch($signup);
                SendSignupToCustomerIo::dispatch($signup);
            }

            // Find all the user's posts.
            $posts = Post::withTrashed()->where('northstar_id', $user['Users Northstar ID'])->get();

            // Anonymize all caption values, delete image URLs from s3, and tag all posts as hide-in-gallery.
            foreach ($posts as $post) {
                $post->text = 'EU Member. Removed because of GDPR';

                $this->aws->deleteImage($post->url);
                $this->fastly->purgeKey('post-'.$post->id);

                $post->url = null;

                if (! $post->tagNames()->contains('Hide In Gallery')) {
                    $post->tag('Hide In Gallery');
                }

                $post->save();

                // Business Logic
                SendPostToQuasar::dispatch($post);
                SendPostToCustomerIo::dispatch($post);
            }
        }

        // Tell everyone we're done!
        $this->info('rogue:gdpr: ALL DONE!');
    }
}
