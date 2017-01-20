<?php

namespace Rogue\Jobs;

use Rogue\Models\Post;
use Rogue\Services\Phoenix;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Rogue\Services\Registrar;

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
    public function handle(Registrar $registrar)
    {
        $phoenix = new Phoenix;
        $drupal_id = $registrar->find($this->post->signup->northstar_id)->drupal_id;

        // Data that every post will have
        $body = [
            'uid' => $drupal_id,
            'nid' => $this->post->signup->campaign_id,
            'quantity' => $this->post->signup->quantity_pending,
            'why_participated' => $this->post->signup->why_participated,
        ];

        // Data that everything except an update without a file will have
        if ($this->hasFile) {
            $body['file_url'] = is_null($this->post->content->edited_file_url) ? $this->photo->file_url : $this->post->content->edited_file_url;
            $body['caption'] = isset($this->post->content->caption) ? $this->post->content->caption : null;
            $body['source'] = $this->post->content->source;
            $body['remote_addr'] = $this->post->content->remote_addr;
        }

        $phoenix->postReportback($body);
    }
}
