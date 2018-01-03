<?php

namespace Rogue\Services;

use Rogue\Jobs\SendSignupToBlink;
use Rogue\Repositories\SignupRepository;

class SignupService
{
    /*
     * SignupRepository Instance
     *
     * @var Rogue\Repositories\SignupRepository;
     */
    protected $signup;

    /**
     * Constructor
     *
     * @param SignupRepository $signup
     * @param Blink $blink
     */
    public function __construct(SignupRepository $signup)
    {
        $this->signup = $signup;
    }

    /*
     * Handles all business logic around creating signups.
     *
     * @param array $data
     * @param string $transactionId
     * @return Illuminate\Database\Eloquent\Model $model
     */
    public function create($data, $transactionId)
    {
        $signup = $this->signup->create($data);

        // Send to Blink unless 'dont_send_to_blink' is TRUE
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);

        // Save the new signup in Customer.io, via Blink.
        if (config('features.blink') && $should_send_to_blink) {
            SendSignupToBlink::dispatch($signup);
        }

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        // Log that a signup was created.
        info('signup_created', ['id' => $signup->id]);

        return $signup;
    }

    /*
     * Handles all business logic around retrieving a signup.
     *
     * @param  string $northstarId
     * @param  int $campaignId
     * @param  int $campaignRunId
     * @return \Rogue\Models\Signup|null
     */
    public function get($northstarId, $campaignId, $campaignRunId)
    {
        $signup = $this->signup->get($northstarId, $campaignId, $campaignRunId);

        return $signup;
    }
}
