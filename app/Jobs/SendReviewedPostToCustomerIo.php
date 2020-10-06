<?php

namespace Rogue\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rogue\Models\Post;
use Rogue\Services\CustomerIo;

class SendReviewedPostToCustomerIo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

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
    public function handle(CustomerIo $customerIo)
    {
        $throttler = Redis::throttle('customerio')
            ->allow(10)
            ->every(1);

        $throttler->then(
            // Rate limit Customer.io API requests to 10/s:
            function () use ($customerIo) {
                $customerIo->trackEvent(
                    $this->post->northstar_id,
                    'campaign_review',
                    $this->post->toCustomerIoPayload(),
                );
            },
            // If we  can't obtain a lock, release to queue:
            function () {
                return $this->release(10);
            },
        );
    }
}
