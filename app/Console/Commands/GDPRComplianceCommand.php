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
    protected $signature = 'rogue:GDPRcompliance {path}';

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
}
