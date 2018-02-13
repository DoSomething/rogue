<?php

namespace Rogue\Jobs;

use Rogue\Models\Post;
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
    public function handle()
    {
        // Format the payload
        $payload = $this->post->toQuasarPayload();

        // Send to Quasar
        $shouldSendToQuasar = config('features.pushToQuasar');
        if ($shouldSendToQuasar) {
            gateway('blink')->post('v1/events/quasar-relay', $payload);
        }

        // Log
        $verb = $shouldSendToQuasar ? 'sent' : 'would have been sent';
        info('Post ' . $this->post->id . ' ' . $verb . ' to Quasar');
    }
}
