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
        if ($request->query('orderBy') === 'desc') {
            // $query = $this->newQuery(Signup::class)->with('posts')->orderBy('posts.created_at', 'desc');
            $query = Signup::with(['posts' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }]);
        } else {
            $query = $this->newQuery(Signup::class)->with('posts');
        }

        $filters = $request->query('filter');

        $query = $this->filter($query, $filters, Signup::$indexes);

        return $this->paginatedCollection($query, $request);
    }
}
