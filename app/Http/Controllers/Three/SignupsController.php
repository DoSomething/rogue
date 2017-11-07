<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\SignupService;
use Rogue\Http\Controllers\Api\ApiController;
use Rogue\Http\Transformers\Three\SignupTransformer;
use Rogue\Http\Controllers\Traits\TransformsRequests;

class SignupsController extends ApiController
{
    use TransformsRequests;

    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

    /**
     * The signup service instance.
     *
     * @var Rogue\Services\SignupService
     */
    protected $signups;

    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct(SignupService $signups)
    {
        $this->signups = $signups;

        $this->transformer = new SignupTransformer;

        $this->middleware('auth.api');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'northstar_id' => 'required|string',
            'campaign_id' => 'required',
            'campaign_run_id' => 'int',
            'quantity' => 'int',
            'why_participated' => 'string',
            'source' => 'string|nullable',
        ]);

        $transactionId = incrementTransactionId($request);
        // Check to see if the signup exists before creating one.
        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id'], $request['campaign_run_id']);

        $code = $signup ? 200 : 201;

        if (! $signup) {
            $signup = $this->signups->create($request->all(), $transactionId);
        }

        return $this->item($signup, $code);
    }

    /**
     * Returns signups.
     * GET /signups
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Signup::class);

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Returns a specific signup.
     * GET /signups/:id
     *
     * @param Request $request
     * @param \Rogue\Models\Signup $signup
     * @return Illuminate\Http\Response
     */
    public function show(Request $request, Signup $signup)
    {
        return $this->item($signup, 200, [], $this->transformer, $request->query('include'));
    }

    /**
     * Updates a specific signup.
     * PATCH /signups/:id
     *
     * @param SignupRequest $request
     * @param \Rogue\Models\Signup $signup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Signup $signup)
    {
        $this->validate($request, [
            'quantity' => 'required_without_all:why_participated',
            'why_participated' => 'required_without_all:quantity',
        ]);

        $signup->update(
            $request->only('quantity', 'why_participated')
        );

        return $this->item($signup);
    }

    /**
     * Delete a signup.
     * DELETE /signups/:id
     *
     * @param \Rogue\Models\Signup $signup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Signup $signup)
    {
        $signup->delete();

        return $this->respond('Signup deleted.', 200);
    }
}
