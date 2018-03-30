<?php

namespace Rogue\Http\Controllers\Legacy\Two;

use Rogue\Services\Legacy\Two\PostService;
use Rogue\Services\Legacy\Two\SignupService;
use Rogue\Http\Requests\Legacy\Two\SignupRequest;
use Rogue\Http\Transformers\Legacy\Two\SignupTransformer;

class SignupsController extends ApiController
{
    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

    /**
     * The signup service instance.
     *
     * @var Rogue\Services\Legacy\Two\SignupService
     */
    protected $signups;

    /**
     * The photo repository instance.
     *
     * @var Rogue\Services\Legacy\Two\PostService
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SignupRequest $request)
    {
        // Check to see if the signup exists before creating one.
        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id'], $request['campaign_run_id']);

        $code = $signup ? 200 : 201;

        if (! $signup) {
            $signup = $this->signups->create($request->all());
        }

        // check to see if there is a reportback too aka we are migratin'
        if ($request->has('photo')) {
            // create the photo and tie it to this signup
            foreach ($request->photo as $photo) {
                $this->posts->create($photo, $signup->id);
            }
        }

        return $this->item($signup, $code);
    }
}
