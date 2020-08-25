<?php

namespace Rogue\Http\Controllers;

use Illuminate\Http\Request;
use Rogue\Http\Transformers\ClubTransformer;
use Rogue\Models\Club;

class ClubsController extends ApiController
{
    /**
     * @var Rogue\Http\Transformers\ClubTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->transformer = new ClubTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Club::class);

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Club::$indexes);

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rogue\Models\Club  $club
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club)
    {
        return $this->item($club);
    }
}
