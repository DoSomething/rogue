<?php

namespace Rogue\Jobs;

use Rogue\Models\Signup;
use Rogue\Mail\ExportDone;
use Illuminate\Bus\Queueable;
use Rogue\Services\ExportService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExportSignups implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaignId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ExportService $export)
    {
        $export = $export->exportSignups($this->campaignId);

        Mail::to('ssmith@dosomething.org')->queue(new ExportDone($this->campaignId, $export));
    }
}
