<?php

namespace Rogue\Console\Commands;

use League\Csv\Reader;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use Illuminate\Console\Command;
use Rogue\Jobs\SendSignupToQuasar;
use Rogue\Jobs\SendSignupToCustomerIo;

class GDPRComplianceCommand extends Command
{
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
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
        info('rogue:GDPRcompliance: Loading in csv from ' . $path);

        $temp = tempnam('temp', 'command_csv');
        file_put_contents($temp, fopen($this->argument('path'), 'r'));

        // Load users from the CSV
        $users_csv = Reader::createFromPath($temp, 'r');
        $users_csv->setHeaderOffset(0);
        $users = $users_csv->getRecords();

        // Anonymize user data.
        info('rogue:GDPRcompliance: Anonymizing data...');
        foreach ($users as $user) {
            dd($user);
        }
    }
}
