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

    protected $campaign;

    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign, $email)
    {
        $this->campaign = $campaign;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ExportService $export)
    {
        // Generate an export of signups for this campaign.
        $export = $export->exportSignups($this->campaign['id']);

        Mail::to($this->email)->queue(new ExportDone($this->campaign, $export));
    }
}
