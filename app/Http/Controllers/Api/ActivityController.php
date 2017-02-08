<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use Rogue\Http\Transformers\ActivityTransformer;

class ActivityController extends ApiController
{
    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     */
    public function __construct()
    {

    }

    /**
     * Returns user activity.
     */
    public function index()
    {
        $signups = Signup::all();

        $this->transformer = new ActivityTransformer;

        return $this->collection($signups, 200);
    }
}
