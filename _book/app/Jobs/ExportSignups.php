<?php

namespace Rogue\Jobs;

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

    /**
     * The full campaign array with campaign details.
     *
     * @var array
     */
    protected $campaign;

    /**
     * The email to send the export to.
     *
     * @var string
     */
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
        // @TODO - Currently limiting the scope of this file to the current run of the campaign.
        // when we rid ourselves of campaign runs and/if exports becomes popular feature we can figure out
        // other ways to limit signups for a particular run of a campaign.
        if (! isset($this->campaign['campaign_runs']['current']['en-global']['id'])) {
            $campaignRun = $this->campaign['campaign_runs']['current']['en']['id'];
        } else {
            $campaignRun = $this->campaign['campaign_runs']['current']['en-global']['id'];
        }

        // Generate an export of signups for this campaign and campaign run.
        $export = $export->exportSignups($this->campaign['id'], $campaignRun);

        Mail::to($this->email)->queue(new ExportDone($this->campaign, $campaignRun, $export));
    }
}
