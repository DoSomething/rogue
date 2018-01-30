<?php

namespace Rogue\Jobs;

use Rogue\Models\Post;
use Rogue\Services\Blink;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPostToQuasar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The post to send to Quasar via Blink.
     *
     * @var Post
     */
    protected $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Blink $blink)
    {
        // Format the payload
        $body = $this->post->toQuasarPayload();

        // Send to Quasar
        $blink->sendToQuasar($body);

        // Log
        info('Post ' . $this->post->id . ' sent to Quasar');
    }
}
