<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\PostService;
use Rogue\Services\SignupService;
use Rogue\Http\Requests\SignupRequest;
use Rogue\Http\Transformers\SignupTransformer;

class SignupsController extends ApiController
{
    /**
     * The signup service instance.
     *
     * @var Rogue\Services\SignupService
     */
    protected $signups;

    /**
     * The photo repository instance.
     *
     * @var Rogue\Services\PostService
     */
    protected $posts;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(SignupService $signups, PostService $posts)
    {
        $this->signups = $signups;
        $this->posts = $posts;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SignupRequest $request)
    {
        $transactionId = incrementTransactionId($request);

        // Check to see if the signup exists before creating one.
        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id'], $request['campaign_run_id']);

        $code = $signup ? 200 : 201;

        if (! $signup) {
            $signup = $this->signups->create($request->all(), $transactionId);
        }

        // check to see if there is a reportback too aka we are migratin'
        if ($request->has('photo')) {
            // create the photo and tie it to this signup
            foreach ($request->photo as $photo) {
                $this->posts->create($photo, $signup->id, $transactionId);
            }
        }

        return $this->item($signup, $code);
    }
}
