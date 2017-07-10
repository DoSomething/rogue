<?php

namespace Rogue\Services;

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
     * @param string $transactionId
     * @return Illuminate\Database\Eloquent\Model $model
     */
    public function create($data, $transactionId)
    {
        $signup = $this->signup->create($data);

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        return $signup;
    }

    /**
     * Handles all business logic around updating signups.
     *
     * @param \Rogue\Models\Signup $signup
     * @param array $data
     * @param string $transactionId
     *
     * @return \Illuminate\Database\Eloquent\Model $model
     */
    public function update($signup, $data, $transactionId)
    {
        $signup = $this->signup->update($signup, $data);

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
