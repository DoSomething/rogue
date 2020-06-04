<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Rogue\Http\Controllers\Controller;

class GroupsController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->rules = [
            'name' => 'required',
            'goal' => 'nullable|integer',
        ];
    }

    /**
     * Create a new group.
     */
    public function create($groupTypeId)
    {
        return view('groups.create')->with(['groupTypeId' => (int) $groupTypeId]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $values = $this->validate($request, array_merge_recursive($this->rules, [
            'name' => [Rule::unique('groups')->where('group_type_id', $request->group_type_id)],
        ]));

        $group = Group::create($request->all());

        // Log that a group was created.
        info('group', ['id' => $group->id]);

        return redirect('groups/' . $group->id);
    }

    /**
     * Edit an existing group.
     *
     * @param  \Rogue\Models\Group  $group
     */
    public function edit(Group $group)
    {
        return view('groups.edit')->with([
            'group' => $group,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Rogue\Models\Group  $group
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Group $group, Request $request)
    {
        $this->validate($request, $this->rules);

        $group->update($request->all());

        // Log that a group was updated.
        info('group_updated', ['id' => $group->id]);

        return redirect('groups/' . $group->id);
    }
}
