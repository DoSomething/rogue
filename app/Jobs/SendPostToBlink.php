<?php

namespace Rogue\Jobs;

use Rogue\Models\Post;
use Illuminate\Bus\Queueable;
use DoSomething\Gateway\Blink;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPostToBlink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     *
     * @var Post to send to Blink.
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
        $this->blink = $blink;
        $payload = $this->post->toBlinkPayload();

        $this->blink->userSignupPost($payload);
        logger()->info('Post ' . $payload['id'] . ' sent to Blink');
    }
}
