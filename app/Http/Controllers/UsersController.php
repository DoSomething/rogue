<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\User;
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
}
