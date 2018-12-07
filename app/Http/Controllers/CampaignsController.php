<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Campaign;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\CampaignTransformer;

class CampaignsController extends ApiController
{
    /**
     * @var Rogue\Http\Transformers\SignupTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     */
    public function __construct()
    {
        $this->transformer = new CampaignTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Campaign::class);

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        return $this->item($campaign);
    }
}
