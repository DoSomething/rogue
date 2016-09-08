<?php

namespace Rogue\Jobs;

use Rogue\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Rogue\Services\Phoenix;

class SendReportbackToPhoenix extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $nid;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($nid, $body)
    {
        $this->phoenix = new Phoenix;
        $this->nid = $nid;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        dd('made it to the job!');
        return $this->phoenix->postReportback($this->nid, $this->body);
    }
}
