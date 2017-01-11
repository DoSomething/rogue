<?php

namespace Rogue\Http\Controllers\Api;

use Illuminate\Http\Request;
use Rogue\Services\SignupService;
use Rogue\Http\Transformers\SignupTransformer;
use Rogue\Repositories\PhotoRepository;

// use Rogue\Http\Requests;

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
     * @var Rogue\Repositories\PhotoRepository
     */
    protected $photo;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(SignupService $signups, PhotoRepository $photo)
    {
        $this->signups = $signups;
        $this->photo = $photo;
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
        	$code = '200';
        }

        // Transform the data to return it
        $this->transformer = new SignupTransformer;

        // check to see if there is a reportback too aka we are migratin'
        if (array_key_exists('photo', $request->all())) {
			// create the photo and tie it to this signup
			$this->photo->create($request->all()['photo'], $signup);
        }

        return $this->item($signup, $code);


    }
}
