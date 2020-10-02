<?php

namespace Rogue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Rogue\Http\Transformers\ActionStatTransformer;
use Rogue\Models\ActionStat;

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
        $this->transformer = new ActionStatTransformer();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(ActionStat::class);
        $tableName = $query->getModel()->getTable();

        $filters = $request->query('filter');

        /**
         * Because we may be joining on groups, which also have location and school_id columns, we
         * specify our table name to avoid integrity constraint violations for ambiguous clauses.
         */

        if (Arr::has($filters, 'action_id')) {
            $query->where('action_id', $filters['action_id']);
        }

        if (Arr::has($filters, 'location')) {
            $query->where($tableName . '.location', $filters['location']);
        }

        if (Arr::has($filters, 'group_type_id')) {
            $query = $query->inGroupTypeId($filters['group_type_id']);
        }

        if (Arr::has($filters, 'school_id')) {
            $query->where($tableName . '.school_id', $filters['school_id']);
        }

        // Allow ordering results:
        $orderBy = $request->query('orderBy');
        $query = $this->orderBy($query, $orderBy, ActionStat::$sortable);

        if ($cursor = Arr::get($request->query('cursor'), 'after')) {
            $query->whereAfterCursor($cursor);

            // Using 'cursor' implies cursor pagination:
            $this->useCursorPagination = true;
        }

        return $this->paginatedCollection($query, $request);
    }
}
