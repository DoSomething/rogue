<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Http\Controllers\Api\ApiController;
use Rogue\Http\Transformers\Three\SignupTransformer;

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
        $this->transformer = new SignupTransformer;
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
