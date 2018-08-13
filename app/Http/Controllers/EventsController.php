<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Event;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\EventTransformer;

class EventsController extends ApiController
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->transformer = new EventTransformer();

        $this->middleware('scopes:activity');
        $this->middleware('role:admin,staff');
    }

    /**
     * Returns events.
     * GET /events
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Event::class);

        $filters = $request->query('filter');

        $query = $this->filter($query, $filters, Event::$indexes);

        if ($filters['signup_id']) {
            $query = $query->forSignup($filters['signup_id'])->orderBy('created_at', 'desc');
        }

        return $this->paginatedCollection($query, $request);
    }
}
