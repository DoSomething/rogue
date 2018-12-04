<?php

namespace Rogue\Managers\Legacy\Two;

use Rogue\Jobs\SendSignupToQuasar;
use Rogue\Jobs\SendSignupToCustomerIo;
use Rogue\Repositories\Legacy\Two\SignupRepository;

class SignupManager
{
    /*
     * SignupRepository Instance
     *
     * @var Rogue\Repositories\Legacy\Two\SignupRepository;
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
     * @return Illuminate\Database\Eloquent\Model $model
     */
    public function create($data)
    {
        $signup = $this->signup->create($data);

        // Send to Blink unless 'dont_send_to_blink' is TRUE
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);

        // Save the new signup in Customer.io, via Blink.
        if (config('features.blink') && $should_send_to_blink) {
            SendSignupToCustomerIo::dispatch($signup);
        }

        // Dispatch job to send signup to Quasar
        SendSignupToQuasar::dispatch($signup);

        // Log that a signup was created.
        info('signup_created', ['id' => $signup->id, 'northstar_id' => $signup->northstar_id, 'signup_created_source' => $signup->source]);

        return $signup;
    }

    /*
     * Handles all business logic around retrieving a signup.
     *
     * @param  string $northstarId
     * @param  int $campaignId
     * @return \Rogue\Models\Signup|null
     */
    public function get($northstarId, $campaignId)
    {
        $signup = $this->signup->get($northstarId, $campaignId);

        return $signup;
    }
}
