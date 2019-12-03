<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\ActionStat;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\ActionStatTransformer;

class ActionStatsController extends ApiController
{
    /**
     * @var Rogue\Http\Transformers\ActionTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->transformer = new ActionStatTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(ActionStat::class);

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, ActionStat::$indexes);

        return $this->paginatedCollection($query, $request);
    }
}
