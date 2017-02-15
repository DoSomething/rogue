<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Rogue\Http\Transformers\ActivityTransformer;
use Rogue\Http\Transformers\SignupTransformer;

class ActivityController extends ApiController
{
    /**
     * @var SignupTransformer;
     */
    protected $transformer;

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
        // Create an empty Signup query, which we can either filter (below)
        // or paginate to retrieve all signup records.
        $query = $this->newQuery(Signup::class);

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Signup::$indexes);

        return $this->paginatedCollection($query, $request);
    }
}
