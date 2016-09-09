<?php

namespace Rogue\Jobs;

use Rogue\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Rogue\Services\Phoenix;
use Rogue\Models\Reportback;

class SendReportbackToPhoenix extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $reportback;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reportback $reportback)
    {
        $this->phoenix = new Phoenix;
        $this->reportback = $reportback;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $phoenix = new Phoenix;

        $body = [
            'uid' => $this->reportback->drupal_id,
            'nid' => $this->reportback->campaign_id,
            'quantity' => $this->reportback->quantity,
            'why_participated' => $this->reportback->why_participated,
            'file_url' => $this->reportback->items()->first()->file_url,
            'caption' => $this->reportback->items()->first()->caption,
            'source' => $this->reportback->items()->first()->source,
        ];

        return $phoenix->postReportback($this->reportback->campaign_id, $body);
    }
}
