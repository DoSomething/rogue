<?php

namespace Rogue\Jobs;

use Rogue\Jobs\Middleware\CustomerIoRateLimit;
use Rogue\Models\Signup;
use Rogue\Services\CustomerIo;

class SendSignupToCustomerIo extends Job
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
        return [new CustomerIoRateLimit()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CustomerIo $customerIo)
    {
        $userId = $this->signup->northstar_id;
        $payload = $this->signup->toCustomerIoPayload();

        $customerIo->trackEvent($userId, 'campaign_signup', $payload);
    }
}
