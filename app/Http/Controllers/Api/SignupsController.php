<?php

namespace Rogue\Http\Controllers\Api;

use Illuminate\Http\Request;
use Rogue\Services\PostService;
use Rogue\Services\SignupService;
use Rogue\Http\Transformers\SignupTransformer;

class SignupsController extends ApiController
{
    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

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
        $this->middleware('api');

        $this->signups = $signups;
        $this->posts = $posts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transactionId = incrementTransactionId($request);

        $signup = $this->signups->create($request->all(), $transactionId);

        if ($signup) {
            $code = 201;
        }

        // Transform the data to return it
        $this->transformer = new SignupTransformer;

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
