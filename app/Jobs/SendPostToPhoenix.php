<?php

namespace Rogue\Jobs;

use Rogue\Models\Post;
use Rogue\Services\Phoenix;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPostToPhoenix extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    /*
     * Post instance.
     *
     * @var Rogue\Models\Post
     */
    protected $post;

    /*
     * @var bool
     */
    protected $hasFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post, $hasFile = true)
    {
        $this->post = $post;

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
            'nid' => $this->post->signup->campaign_id,
            'quantity' => $this->post->signup->quantity,
            'why_participated' => $this->post->signup->why_participated,
        ];

        // Data that everything except an update without a file will have
        if ($this->hasFile) {
            $body['file_url'] = is_null($this->post->postData->edited_file_url) ? $this->photo->file_url : $this->post->postData->edited_file_url;
            $body['caption'] = isset($this->post->postData->caption) ? $this->post->postData->caption : null;
            $body['source'] = $this->post->postData->source;
            $body['remote_addr'] = $this->post->postData->remote_addr;
        }

        $phoenix->postReportback($body);
    }
}
