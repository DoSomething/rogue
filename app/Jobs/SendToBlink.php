<?php

namespace Rogue\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DoSomething\Gateway\Blink;

class SendToBlink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Blink API client.
     *
     * @var \DoSomething\Gateway\Blink
     */
    protected $blink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Blink $blink)
    {
        $this->blink = $blink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($payload)
    {
        // If there is a signup_id, this is a userSignupPost.
        // Otherwise, it is a userSignup.
        if ($payload['signup_id']) {
            $this->blink->userSignupPost($payload);
            logger()->info('Post ' . $payload['id'] . ' sent to Blink');
        } else {
            $this->blink->userSignup($payload);
            logger()->info('Signup ' . $payload['id'] . ' sent to Blink');
        }
    }
}
