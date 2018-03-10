<?php

namespace Rogue\Jobs;

use Rogue\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendReviewedPostToCustomerIo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The post to send to Customer.io.
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
        $payload = $this->post->toBlinkPayload();

        // Send to Quasar
        $shouldSendToCIO = config('features.blink');

        if ($shouldSendToCIO) {
            gateway('blink')->post('v1/events/user-signup-post-review', $payload);
        }

        // Log
        $verb = $shouldSendToCIO ? 'sent' : 'would have been sent';
        info('Review of post ' . $this->post->id . ' ' . $verb . ' to Customer.io');
    }
}
