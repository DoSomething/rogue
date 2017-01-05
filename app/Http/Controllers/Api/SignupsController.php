<?php

namespace Rogue\Http\Controllers\Api;

use Illuminate\Http\Request;
use Rogue\Services\SignupService;
use Rogue\Http\Transformers\SignupTransformer;


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
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(SignupService $signups)
    {
        $this->signups = $signups;
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

        // get the data into the way we want to return it
        $this->transformer = new SignupTransformer;

        return $this->item($signup, $code);


    	// return 'hit the store signup endpoint :)';
    }
}
