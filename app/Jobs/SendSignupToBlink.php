<?php

namespace Rogue\Jobs;

use Rogue\Models\Signup;
use Illuminate\Bus\Queueable;
use DoSomething\Gateway\Blink;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSignupToBlink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The signup to send to Blink.
     *
     * @var Signup
     */
    protected $signup;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Signup $signup)
    {
        $this->signup = $signup;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Blink $blink)
    {
        $payload = $this->signup->toBlinkPayload();

        $blink->userSignup($payload);
        logger()->info('Signup ' . $payload['id'] . ' sent to Blink');
    }
}
