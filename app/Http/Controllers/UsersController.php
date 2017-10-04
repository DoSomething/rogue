<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\User;
use Illuminate\Http\Request;
use Rogue\Services\Registrar as Registrar;

class UsersController extends Controller
{
    /**
     * The Northstar API client.
     * @var Northstar
     */
    protected $northstar;

    public function __construct(Registrar $registrar)
    {
        $this->registrar = $registrar;

        $this->middleware('auth');
        $this->middleware('role:admin,staff');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ids = User::all()->pluck('northstar_id')->all();

        $users = $this->registrar->findAll($ids);

        return view('users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id  Northstar ID
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->registrar->find($id);

        return view('users.show')
            ->with('state', ['user' => $user->toArray()]);
    }

    /**
     * Search for users.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->query('query');

        // Redirect empty queries to the user index.
        if ($query === '') {
            return redirect()->route('users.index');
        }

        // Attempt to fetch all users.
        $users = $this->registrar->search($query);

        if (! $users) {
            return redirect()->route('users.index')->with('status', 'No user found!');
        } elseif ($users->count() === 1) {
            return redirect()->route('users.show', [$users->first()->id]);
        }

        return view('users.search')->with(compact('users', 'query'));
    }
}
