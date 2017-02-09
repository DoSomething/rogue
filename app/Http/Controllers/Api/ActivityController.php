<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use App\Http\Controllers\Controller;
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
        $signups = Signup::paginate(15);

        $this->transformer = new ActivityTransformer;

        return $this->collection($signups, 200);
    }
}
