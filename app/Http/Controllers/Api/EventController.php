<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Event;
use Illuminate\Http\Request;
use Rogue\Http\transformers\EventTransformer;

class EventController extends ApiController
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->transformer = new EventTransformer();
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

        if ($filters['signup_id']) {
            $events = $query->forSignup($filters['signup_id'])->orderBy('created_at', 'desc')->get();

            return $this->collection($events);
        }

        return $this->paginatedCollection($query, $request);
    }
}
