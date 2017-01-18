<?php

namespace Rogue\Services;

use Rogue\Repositories\SignupRepository;
use Rogue\Jobs\SendSignupToPhoenix;

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
     * @param \Rogue\Repositories\SignupRepository $signup
     */
    public function __construct(SignupRepository $signup)
    {
        $this->signup = $signup;
    }

    /*
     * Handles all business logic around creating signups.
     *
     * @param array $data
     * @param int $signupId
     * @param string $transactionId
     * @return Illuminate\Database\Eloquent\Model $model
     */
    public function create($data, $transactionId)
    {
        $signup = $this->signup->create($data);

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        // POST reportback back to Phoenix, unless told not to.
        if (! isset($data['do_not_forward'])) {
            dispatch(new SendSignupToPhoenix($signup));
        }

        return $signup;
    }
}
