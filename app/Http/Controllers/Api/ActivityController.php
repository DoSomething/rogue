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
        $query = $this->newQuery(Signup::class)->with('posts');

        if ($request->query('orderBy') === 'desc') {
            $query = $query->orderBy('created_at', 'desc');
        }

        $filters = $request->query('filter');

        $query = $this->filter($query, $filters, Signup::$indexes);

        return $this->paginatedCollection($query, $request, 200, [], null);
    }
}
