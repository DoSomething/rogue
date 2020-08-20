<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Models\Club;
use Illuminate\Http\Request;
use Rogue\Http\Controllers\Controller;

class ClubsController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->rules = [
            'name' => 'required|string|max:255',
            'leader_id' => 'required|objectid|unique:clubs',
            'city' => 'nullable|string',
            'location' => 'nullable|iso3166',
            'school_id' => 'nullable|string|max:255',
        ];
    }

    /**
     * Create a new club.
     */
    public function create()
    {
        return view('clubs.create');
    }

    /**
     * Store a newly created club in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $club = Club::create($request->all());

        // Log that a club was created.
        info('club_created', ['id' => $club->id]);

        return redirect('clubs/'. $club->id .'/edit')->with('status', 'Club successfully created!');
    }

    /**
     * Edit an existing club.
     *
     * @param  \Rogue\Models\Club  $club
     */
    public function edit(Club $club)
    {
        return view('clubs.edit')->with([
            'club' => $club,
        ]);
    }

    /**
     * Update the specified club in storage.
     *
     * @param  \Rogue\Models\Club  $club
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Club $club, Request $request)
    {
        $this->validate($request, $this->rules);

        $club->update($request->all());

        // Log that a club was updated.
        info('club_updated', ['id' => $club->id]);

        return redirect()->back()->with('status', 'Club successfully updated!');
    }
}
