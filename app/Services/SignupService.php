<?php

namespace Rogue\Services;

use DoSomething\Gateway\Blink;
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
     * Blink API client.
     *
     * @var \DoSomething\Gateway\Blink
     */
    protected $blink;

    /**
     * Constructor
     *
     * @param SignupRepository $signup
     * @param Blink $blink
     */
    public function __construct(SignupRepository $signup, Blink $blink)
    {
        $this->signup = $signup;
        $this->blink = $blink;
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
            $payload = $signup->toBlinkPayload();
            $this->blink->userSignup($payload);
            logger()->info('Signup ' . $signup->id . ' sent to Blink');
        }

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

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
