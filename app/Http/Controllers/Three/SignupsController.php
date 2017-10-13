<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\v3SignupTransformer;
use Rogue\Models\Signup;

class SignupsController extends ApiController
{
    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct()
    {
        $this->transformer = new v3SignupTransformer;
    }

    /**
     * Returns signups.
     * GET /signups
     *
     * @param Request $request
     * @return ]Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Signup::class);

        return $this->paginatedCollection($query, $request);
    }
}
