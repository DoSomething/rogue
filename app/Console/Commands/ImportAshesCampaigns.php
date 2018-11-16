<?php

namespace Rogue\Console\Commands;

use Carbon\Carbon;
use League\Csv\Reader;
use Rogue\Models\Campaign;
use Rogue\Services\Registrar;
use Illuminate\Console\Command;

class ImportAshesCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:ashescampaignsimport {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports legacy campaigns from Ashes based on the data in the given (link to a) CSV file.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Registrar $registrar)
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

    }
}
