<?php

namespace Rogue\Http\Controllers;

use DoSomething\Northstar\Northstar;

class UsersController extends Controller
{
    /**
     * The Northstar API client.
     * @var Northstar
     */
    protected $northstar;

    public function __construct(Northstar $northstar)
    {
        $this->northstar = $northstar;

        $this->middleware('auth');
        $this->middleware('role:admin,staff');
    }
}
