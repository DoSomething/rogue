<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Group;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\GroupTransformer;

class GroupsController extends ApiController
{
    /**
     * @var Rogue\Http\Transformers\GroupTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->transformer = new GroupTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Group::class);

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Group::$indexes);

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rogue\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return $this->item($group);
    }
}
