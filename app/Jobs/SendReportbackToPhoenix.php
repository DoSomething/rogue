<?php

namespace Rogue\Jobs;

use Rogue\Services\Phoenix;
use Rogue\Models\Reportback;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReportbackToPhoenix extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $reportback;
    protected $hasFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reportback $reportback, $hasFile = true)
    {
        $this->reportback = $reportback;
        $this->hasFile = $hasFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $phoenix = new Phoenix;

        // Data that every post will have
        $body = [
            'uid' => $this->reportback->drupal_id,
            'nid' => $this->reportback->campaign_id,
            'quantity' => $this->reportback->quantity,
            'why_participated' => $this->reportback->why_participated,
        ];

        // Data that everything except an update without a file will have
        if ($this->hasFile) {
            $reportbackItem = $this->reportback->items()->orderBy('created_at', 'desc')->first();
            $body['file_url'] = is_null($reportbackItem->edited_file_url) ? $reportbackItem->file_url : $reportbackItem->edited_file_url;
            $body['caption'] = isset($reportbackItem->caption) ? $reportbackItem->caption : null;
            $body['source'] = $reportbackItem->source;
        }

        $phoenix->postReportback($this->reportback->campaign_id, $body);
    }
}
