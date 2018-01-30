<?php

namespace Rogue\Jobs;

use Rogue\Models\Signup;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Rogue\Http\Resources\SignupResource;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSignupToQuasar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The signup to send to Quasar via Blink.
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
    public function handle()
    {
        info('in the job');

        $body = $this->signup->toQuasarPayload();
        info($body);

        // send to Quasar (need endpoint to hit)

        // log
        info('Signup ' . $this->signup->id . ' sent to Quasar');

    }
}
