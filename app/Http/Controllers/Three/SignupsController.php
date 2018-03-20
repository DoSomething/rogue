<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\Three\SignupService;
use Rogue\Http\Controllers\Api\ApiController;
use Illuminate\Auth\Access\AuthorizationException;
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
     * @var \Rogue\Services\Three\SignupService
     */
    protected $signups;

    /**
     * Use cursor pagination for these routes.
     *
     * @var bool
     */
    protected $useCursorPagination = true;

    /**
     * Create a controller instance.
     *
     * @param SignupService $signups
     */
    public function __construct(SignupService $signups)
    {
        $this->signups = $signups;
        $this->transformer = new SignupTransformer;

        $this->middleware('auth:api', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('role:admin', ['only' => ['destroy']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'campaign_id' => 'required',
            'campaign_run_id' => 'int',
            'why_participated' => 'string',
        ]);

        $northstarId = getNorthstarId($request);

        // Check to see if the signup exists before creating one.
        $signup = $this->signups->get($northstarId, $request['campaign_id'], $request['campaign_run_id']);

        $code = $signup ? 200 : 201;

        if (! $signup) {
            $signup = $this->signups->create($request->all(), $northstarId);
        }

        return $this->item($signup, $code);
    }

    /**
     * Returns signups.
     * GET /signups
     *
     * @param \Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Signup::class);

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Signup::$indexes);

        // Only allow an admin or the user who owns the signup to see the signup's unapproved posts.
        if ($request->query('include') === 'posts') {
            $query = $query->withVisiblePosts();
        }

        $orderBy = $request->query('orderBy');

        if ($orderBy) {
            list($column, $direction) = explode(',', $orderBy, 2);

            if (in_array($column, Signup::$indexes)) {
                dd('got a column');
                $query = $query->orderBy($column, $direction);
            }

            if ($column === 'accepted_quantity') {
                // $query = $query->withAcceptedQuantity();
                $query->with(['posts' => function ($query) {
                    $query->where('status', 'accepted');
                }]);
                // $query->select('*', \DB::raw('SUM(posts.quantity) as accepted_quantity'));

                // $query = $query->orderBy(\DB::raw('accepted_quantity'), $direction);

                // $query->select('*', \DB::raw('SUM(quantity) as accepted_quantity'));
            }
        }

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Returns a specific signup.
     * GET /signups/:id
     *
     * @param \Illuminate\Http\Request $request
     * @param \Rogue\Models\Signup $signup
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Signup $signup)
    {
        // Only allow an admin or the user who owns the signup to see the signup's unapproved posts.
        if ($request->query('include') === 'posts') {
            $signup = Signup::withVisiblePosts()->first();
        }

        return $this->item($signup, 200, [], $this->transformer, $request->query('include'));
    }

    /**
     * Updates a specific signup.
     * PATCH /signups/:id
     *
     * @param \Illuminate\Http\Request $request
     * @param \Rogue\Models\Signup $signup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Signup $signup)
    {
        $validatedRequest = $this->validate($request, [
            'why_participated' => 'required',
        ]);

        // Only allow an admin or the user who owns the signup to update.
        if (token()->role() === 'admin' || auth()->id() === $signup->northstar_id) {
            // why_participated is the only thing that can be changed
            $this->signups->update($signup, $validatedRequest);

            return $this->item($signup);
        }

        throw new AuthorizationException('You don\'t have the correct role to update this signup!');
    }

    /**
     * Delete a signup.
     * DELETE /signups/:id
     *
     * @param \Rogue\Models\Signup $signup
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Signup $signup)
    {
        $trashed = $this->signups->destroy($signup->id);

        if ($trashed) {
            return $this->respond('Signup deleted.', 200);
        }

        return response()->json(['code' => 500, 'message' => 'There was an error deleting the post']);
    }
}
