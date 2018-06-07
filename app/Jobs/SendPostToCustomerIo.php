<?php

namespace Rogue\Jobs;

use Rogue\Models\Post;
use Illuminate\Bus\Queueable;
use DoSomething\Gateway\Blink;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPostToCustomerIo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The post to send to Blink.
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
    public function handle(Blink $blink)
    {
        // Check if the post still exists before sending (might have been deleted immediately if created in Runscope test).
        if ($this->post && $this->post->signup) {
            $payload = $this->post->toBlinkPayload();
            $blink->userSignupPost($payload);

            if ($this->log) {
                logger()->info('Post ' . $payload['id'] . ' sent to Blink');
            }
        }
    }
}
