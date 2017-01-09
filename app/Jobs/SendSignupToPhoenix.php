<?php

namespace Rogue\Jobs;

use Rogue\Services\Phoenix;
use Rogue\Models\Signup;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSignupToPhoenix extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

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
        $phoenix = new Phoenix;
        // @TODO: get uid from northstar

		// format the data how phoenix wants it as the $body here
		// give them uid and source
        $body = [
        	// include user here
        	// this is a dummy user aka me lol
        	'uid' => '1705523',
            'source' => $this->signup->source,
        ];

        $campaign_id = $this->signup->campaign_id;

        $phoenix->postSignup($body, $campaign_id);
    }
}
