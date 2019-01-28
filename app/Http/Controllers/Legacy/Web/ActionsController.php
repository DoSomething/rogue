<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Action;
use Illuminate\Http\Request;
use Rogue\Http\Controllers\Controller;


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
        $this->middleware('auth');
        $this->middleware('role:admin,staff', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
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
            'campaign_id' => 'required|ingeger|unique:campaigns'
            'post_type' => 'required|string',
            'reportback' => 'required|boolean',
            'civic_action' => 'required|boolean',
            'scholarship_entry' => 'required|boolean',
            'active' => 'nullable|boolean',
            'noun' => 'required|string',
            'vert' => 'required|string',
        ]);

        $action = Action::create($request->all());

        // Log that a action was created.
        info('action_created', ['id' => $action->id]);
    }
}
