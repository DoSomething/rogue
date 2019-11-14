<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Campaign;
use Illuminate\Console\Command;

class RecountPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:recount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh post counts for all campaigns.';

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
        foreach (Campaign::cursor() as $campaign) {
            $this->line('Refreshing cached post counts for ' . $campaign->id . '.');
            $campaign->refreshCounts();
        }
    }
}
