<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\SignupTransformer;

class ActivityController extends ApiController
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->transformer = new SignupTransformer();
    }

    /**
     * Returns signup activity.
     * GET /activity
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Create an empty Signup query and eager-load posts, which we
        // can either filter or paginate to retrieve all signup records.
        $query = $this->newQuery(Signup::class);

        $filters = $request->query('filter');

        if (array_has($filters, 'status')) {
            // Constrain Eager Load, only returning posts with status query.
            $query = $query->with(['posts' => function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            }]);

            // Remove status from $filters to query Signups.
            unset($filters['status']);
        } else {
            $query = $query->with('posts');
        }

        $query = $this->filter($query, $filters, Signup::$indexes);

        return $this->paginatedCollection($query, $request);
    }
}
