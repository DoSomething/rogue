<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Campaign;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\CampaignTransformer;

class CampaignsController extends ApiController
{
    /**
     * @var Rogue\Http\Transformers\CampaignTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
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

        if (has_include($request, 'actions')) {
            // Eagerly load the `action` relationship.
            $query->with('actions');
        }

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Campaign::$indexes);

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
