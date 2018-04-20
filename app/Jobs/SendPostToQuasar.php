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
     * Whether or not to log that this Post was sent to Quasar.
     *
     * @var bool
     */
    protected $log;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post, $log = true)
    {
        $this->post = $post;
        $this->log = $log;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Check if the post still exists before sending (might have been deleted immediately if created in Runscope test).
        if ($this->post && $this->post->signup) {
            // Format the payload
            $payload = $this->post->toQuasarPayload();

            // Send to Quasar
            $shouldSendToQuasar = config('features.pushToQuasar');
            if ($shouldSendToQuasar) {
                gateway('blink')->post('v1/events/quasar-relay', $payload);
            }

            // Log
            if ($this->log) {
                $verb = $shouldSendToQuasar ? 'sent' : 'would have been sent';
                info('Post ' . $this->post->id . ' ' . $verb . ' to Quasar');
            }
        }
    }

    /**
     * Get the id of the post we are sending to Quasar
     *
     * @return int
     */
    public function getPostId()
    {
        return $this->post->id;
    }
}
