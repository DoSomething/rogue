<?php

namespace Rogue\Http\Controllers\Legacy\Web;

use Rogue\PostType;
use Rogue\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

        $this->rules = [
            'name' => ['required', 'string'],
            'post_type' => ['required', 'string', Rule::in(PostType::values())],
            'callpower_campaign_id' => ['nullable', 'required_if:post_type,phone-call', 'integer'],
            'noun' => ['required', 'string'],
            'verb' => ['required', 'string'],
        ];
    }

    /**
     * Create a new action.
     */
    public function create($campaignId)
    {
        return view('actions.create')->with([
            'postTypes' => PostType::all(),
            'campaignId' => (int) $campaignId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {

        // Checkbox values are only sent from the front end if they are checked.
        // Get checkbox values if sent from the front end or via the API.
        $request['reportback'] = isset($request['reportback']) && $request['reportback'] ? true : false;
        $request['civic_action'] = isset($request['civic_action']) && $request['civic_action'] ? true : false;
        $request['scholarship_entry'] = isset($request['scholarship_entry']) && $request['scholarship_entry'] ? true : false;
        $request['anonymous'] = isset($request['anonymous']) && $request['anonymous'] ? true : false;
        $this->validate($request, array_merge_recursive($this->rules, [
            'campaign_id' => ['required', 'integer', 'exists:campaigns,id'],
            'callpower_campaign_id' => [Rule::unique('actions')],
        ]));

        // Check to see if the action exists before creating one.
        $action = Action::where([
            'name' => $request['name'],
            'campaign_id' => $request['campaign_id'],
            'post_type' => $request['post_type'],
        ])->first();

        if (! $action) {
            $action = Action::create($request->all());

            // Log that a action was created.
            info('action_created', ['id' => $action->id]);
        }

        return redirect()->route('campaign-ids.show', $request['campaign_id']);
    }

    /**
     * Edit an existing action.
     */
    public function edit($campaignId, $actionId)
    {
        return view('actions.edit')->with([
            'campaignId' => $campaignId,
            'action' => Action::find($actionId),
            'postTypes' => PostType::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Rogue\Models\Action  $action
     */
    public function update(Request $request, Action $action)
    {

        $checkboxes = [
                        'reportback',
                        'civic_action',
                        'scholarship_entry',
                        'anonymous',
                      ];

        foreach ($checkboxes as $checkbox) {
            if (! isset($request[$checkbox])) {
                $request[$checkbox] = 0;
            }
        }
        $this->validate($request, array_merge_recursive($this->rules, [
            'callpower_campaign_id' => [Rule::unique('actions')->ignore($action->id)],
        ]));

        $action->update($request->all());

        // Log that an action was updated.
        info('action_updated', ['id' => $action->id]);

        return redirect()->route('campaign-ids.show', $action->campaign_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Rogue\Models\Action  $campaign
     */
    public function destroy(Action $action)
    {
        $action->forceDelete();

        // Log that an action was deleted.
        info('action_deleted', ['id' => $action->id]);

        return $this->respond('Action deleted.', 200);
    }
}
