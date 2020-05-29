<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\GroupType;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\GroupTypeTransformer;

class GroupTypesController extends ApiController
{
    /**
     * @var Rogue\Http\Transformers\GroupTypeTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->transformer = new GroupTypeTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(GroupType::class);

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rogue\Models\GroupType  $groupType
     * @return \Illuminate\Http\Response
     */
    public function show(GroupType $groupType)
    {
        return $this->item($groupType);
    }
}
