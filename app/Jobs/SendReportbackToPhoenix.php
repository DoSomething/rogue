<?php

namespace Rogue\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Rogue\Services\Phoenix;
use Rogue\Models\Reportback;
use Request;

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

        $reportbackItem = $this->reportback->items()->orderBy('created_at', 'desc')->first();

        $body = [
            'uid' => $this->reportback->drupal_id,
            'nid' => $this->reportback->campaign_id,
            'quantity' => $this->reportback->quantity,
            'why_participated' => $this->reportback->why_participated,
            'file_url' => $reportbackItem->file_url,
            'caption' => $reportbackItem->caption,
            'source' => $reportbackItem->source,
        ];

        $phoenix->postReportback($this->reportback->campaign_id, $body);
    }
}
