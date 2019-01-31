<?php

namespace Rogue\Http\Controllers\Legacy\Web;

use Rogue\Models\Action;
use Illuminate\Http\Request;
use Rogue\Http\Controllers\Controller;


class ActionsController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('role:admin,staff', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'campaign_id' => 'required|integer|exists:campaigns,id',
            'post_type' => 'required|string',
            'reportback' => 'required|boolean',
            'civic_action' => 'required|boolean',
            'scholarship_entry' => 'required|boolean',
            'noun' => 'required|string',
            'verb' => 'required|string',
        ]);

        $action = Action::create($request->all());

        // Log that a action was created.
        info('action_created', ['id' => $action->id]);
    }
}
