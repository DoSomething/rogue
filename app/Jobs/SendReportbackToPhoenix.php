<?php

namespace Rogue\Jobs;

use Rogue\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReportbackToPhoenix extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /*
     * Instance of \Rogue\Services\Phoenix\Phoenix
     */
    protected $phoenix;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Phoenix $phoenix)
    {
        $this->phoenix = $phoenix;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($nid, $body)
    {
        return $this->phoenix->postReportback($nid, $body);
    }
}
