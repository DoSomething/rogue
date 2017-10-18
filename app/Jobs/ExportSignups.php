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
        // Generate an export of signups for this campaign and campaign run.
        // @TODO - Currently limiting the scope of this file to the current run of the campaign.
        // when we rid ourselves of campaign runs and/if exports becomes popular feature we can figure out
        // other ways to limit signups for a particular run of a campaign.
        $export = $export->exportSignups($this->campaign['id'], $campaign['campaign_runs']['current']['en-global']['id']);

        Mail::to($this->email)->queue(new ExportDone($this->campaign, $export));
    }
}
