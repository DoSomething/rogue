<?php

namespace Rogue\Jobs;

use Illuminate\Bus\Queueable;
use Rogue\Services\CustomerIo;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateCustomerIoEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The ID of the user to create the event for.
     *
     * @var string
     */
    protected $userId;

    /**
     * The name of the event to create.
     *
     * @var string
     */
    protected $eventName;

    /**
     * The payload of the event to create.
     *
     * @var array
     */
    protected $eventData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $eventName, $eventData)
    {
        $this->userId = $userId;
        $this->eventName = $eventName;
        $this->eventData = $eventData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CustomerIo $customerIo)
    {
        // Rate limit Customer.io API requests to 10/s.
        $throttler = Redis::throttle('customerio')->allow(10)->every(1);

        $throttler->then(function () use ($customerIo) {
            $response = $customerIo->trackEvent($this->userId, $this->eventName, $this->eventData);

            info("Sent {$this->userId} event to Customer.io for user {$this->userId}", ['response' => $response]);
        }, function () {
            return $this->release(10);
        });
    }
}
