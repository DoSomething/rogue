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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Rogue\Models\Action  $action
     */
    public function update(Request $request, Action $action)
    {
        $this->validate($request, [
            'name' => 'string',
            'post_type' => 'string',
            'reportback' => 'boolean',
            'civic_action' => 'boolean',
            'scholarship_entry' => 'boolean',
            'noun' => 'string',
            'verb' => 'string',
        ]);

        $action->update($request->all());

        // Log that an action was updated.
        info('action_updated', ['id' => $action->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rogue\Models\Action  $campaign
     */
    public function destroy(Action $action)
    {
        $action->delete();

        // Log that an action was deleted.
        info('action_deleted', ['id' => $action->id]);
    }
}
