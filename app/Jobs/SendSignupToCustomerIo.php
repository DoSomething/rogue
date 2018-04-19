<?php

namespace Rogue\Jobs;

use Rogue\Models\Signup;
use Illuminate\Bus\Queueable;
use DoSomething\Gateway\Blink;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSignupToCustomerIo implements ShouldQueue
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
        // Check if the signup still exists before sending (might have been deleted immediately if created in Runscope test).
        if ($this->signup) {
          $payload = $this->signup->toBlinkPayload();

          // @TODO: update other places we call this to not check for config('features.blink')
          $shouldSend = config('features.blink');
          if ($shouldSend) {
              $blink->userSignup($payload);
          }

          // Log
          $verb = $shouldSend ? 'sent' : 'would have been sent';
          info('Signup ' . $payload['id'] . ' ' . $verb . ' to Customer.io');
        }
    }
}
