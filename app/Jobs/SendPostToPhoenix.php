<?php

namespace Rogue\Jobs;

use Rogue\Models\Photo;
use Rogue\Services\Phoenix;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPostToPhoenix extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    /*
     * Photo instance.
     *
     * @var Rogue\Models\Photo
     */
    protected $photo;

    /*
     * @var bool
     */
    protected $hasFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Photo $photo, $hasFile = true)
    {
        $this->photo = $photo;

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
            'uid' => 12345, // Grab drupal_id from northstar object?
            'nid' => $this->photo->signup->campaign_id,
            'quantity' => $this->photo->signup->quantity,
            'why_participated' => $this->photo->signup->why_participated,
        ];

        // Data that everything except an update without a file will have
        if ($this->hasFile) {
            $body['file_url'] = is_null($this->photo->edited_file_url) ? $this->photo->file_url : $this->photo->edited_file_url;
            $body['caption'] = isset($this->photo->caption) ? $this->photo->caption : null;
            $body['source'] = $this->photo->source;
            $body['remote_addr'] = $this->photo->remote_addr;
        }

        $phoenix->postReportback($body);
    }
}
