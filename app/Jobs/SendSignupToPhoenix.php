<?php

namespace Rogue\Jobs;

use Rogue\Models\Signup;
use Rogue\Services\Phoenix;
use Rogue\Services\Registrar;
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

		$registrar = new Registrar;
		$drupal_id = $registrar->find($this->signup->northstar_id)->drupal_id;

        $body = [
            'uid' => $drupal_id,
            'source' => $this->signup->source,
        ];

        $campaign_id = $this->signup->campaign_id;

        $phoenix->postSignup($body, $campaign_id);
    }
}
