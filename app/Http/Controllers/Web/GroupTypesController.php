<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Models\GroupType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Rogue\Http\Controllers\Controller;

class GroupTypesController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->rules = [
            'name' => ['required', 'string', 'unique'],
        ];
    }

    /**
     * Create a new group type.
     */
    public function create()
    {
        return view('group-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $groupType = GroupType::create($request->all());

        // Log that a group type was created.
        info('group_type', ['id' => $groupType->id]);

        return redirect('group-types/' . $gruopType->id);
    }

    /**
     * Edit an existing action.
     *
     * @param  \Rogue\Models\GroupType  $groupType
     * @param  $campaignId
     */
    public function edit(GroupType $groupType)
    {
        return view('group-types.edit')->with([
            'groupType' => $groupType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Rogue\Models\GroupType  $groupType
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(GroupType $groupType, Request $request)
    {
  
        $this->validate($request, $this->rules);

        $groupType->update($request->all());

        // Log that a group type was updated.
        info('group_type_updated', ['id' => $groupType->id]);

        return redirect('group-types/' . $groupType->id);
    }
}
