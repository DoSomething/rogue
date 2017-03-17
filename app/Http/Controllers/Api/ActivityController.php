<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
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
        // $query = $this->newQuery(Signup::class);
        $query = Signup::withCount('post')

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Signup::$indexes);

        return $this->paginatedCollection($query, $request);
    }


    /**
     * Query for total reactions for a photo.
     *
     * @param int $reactionableId
     * @return int total count
     */
    // public static function withReactionCount($reactionableId)
    // {
    //     return self::where('id', $reactionableId)->withCount('reactions')->first();
    // }
}
