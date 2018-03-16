<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Signup;
use Illuminate\Console\Command;

class ReviewPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:review {signup} {--status=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Review all posts under the given signup ID with the given status';

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
        dd($this->argument('signup'));
    }
}
