<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Campaign;
use Illuminate\Console\Command;

class StandardizeCauses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:standardizecauses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates causes on campaigns to be lowercased and hyphenated';

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
        $this->info('rogue:standardizecauses: Starting to clean up the causes!');

        // Grab every campaign that has a cause set
        $campaignsWithCause = Campaign::whereNotNull('cause')->get();

        foreach($campaignsWithCause as $campaign) {
            $this->info('Updating campaign '.$campaign->id);

            $oldCause = $campaign->getOriginal('cause');
            $this->info('--From:'.$oldCause);

            // Make the causes lowercase and replaces spaces with hyphens
            $newCause = str_replace(' ', '-', strtolower($oldCause));
            $this->info('--To:'.$newCause);

            // Re-format the new cause string as the array that our mutator expects
            $newCause = explode(',', $newCause);
            $campaign->cause = $newCause;

            $campaign->save();

            $this->info('--✔ Successfully updated!');
        }

        $this->info('rogue:standardizecauses: All causes cleaned up!');
    }
}
