<?php

namespace Rogue\Jobs;

use Rogue\Jobs\Middleware\GambitRateLimit;
use Rogue\Models\Signup;
use Rogue\Services\Gambit;

class SendSignupToGambit extends Job
{
    /**
     * The signup to send to Customer.io.
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
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new GambitRateLimit()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Gambit $gambit)
    {
        $gambit->relaySignup($this->signup);
    }
}
