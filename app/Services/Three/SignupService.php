<?php

namespace Rogue\Services\Three;

use Rogue\Jobs\SendSignupToBlink;
use Rogue\Jobs\SendSignupToQuasar;
use Rogue\Jobs\SendDeletedSignupToQuasar;
use Rogue\Repositories\Three\SignupRepository;

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

    /**
     * Handles all business logic around creating signups.
     *
     * @param array $data
     * @param string $northstarId
     * @return Illuminate\Database\Eloquent\Model $model
     */
    public function create($data, $northstarId)
    {
        $signup = $this->signup->create($data, $northstarId);

        // Send to Blink unless 'dont_send_to_blink' is TRUE
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);

        // Save the new signup in Customer.io, via Blink.
        if (config('features.blink') && $should_send_to_blink) {
            SendSignupToBlink::dispatch($signup);
        }

        // Dispatch job to send signup to Quasar
        if (config('features.pushToQuasar')) {
            SendSignupToQuasar::dispatch($signup);
        }

        // Log that a signup was created.
        info('signup_created', ['id' => $signup->id]);

        return $signup;
    }

    /**
     * Handles all business logic around updating signups.
     *
     * @param array $data
     * @param string $northstarId
     * @return Illuminate\Database\Eloquent\Model $model
     */
    public function update($signup, $data)
    {
        $signup = $this->signup->update($signup, $data);

        // Dispatch job to send signup to Quasar
        if (config('features.pushToQuasar')) {
            SendSignupToQuasar::dispatch($signup);
        }

        // Log that a signup was updated.
        info('signup_updated', ['id' => $signup->id]);

        return $signup;
    }

    /**
     * Handle all business logic around deleting a signup.
     *
     * @param int $signupId
     * @return bool
     */
    public function destroy($signupId)
    {
        $trashed = $this->signup->destroy($signupId);

        if ($trashed) {
            info('signup_deleted', [
                'id' => $signupId,
            ]);

            // Dispatch job to send post to Quasar
            if (config('features.pushToQuasar')) {
                SendDeletedSignupToQuasar::dispatch($signupId);
            }
        }

        return $trashed;
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
