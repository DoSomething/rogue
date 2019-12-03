<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Post;
use Rogue\Models\Action;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\ActionTransformer;

class ActionsController extends ApiController
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
        $this->transformer = new ActionTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Action::class);

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Action::$indexes);

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rogue\Models\Action  $action
     * @return \Illuminate\Http\Response
     */
    public function show(Action $action)
    {
        return $this->item($action);
    }

    /**
     * Display statistics for specified resource and given school ID.
     *
     * @param  \Rogue\Models\Action  $action
     * @param  string  $schoolId
     * @return \Illuminate\Http\Response
     */
    public function showSchoolStats(Action $action, string $schoolId)
    {
        return [
            'quantity' => (int) Post::getAcceptedQuantitySum($action, $schoolId),
        ];
    }
}
