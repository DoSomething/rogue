<?php

namespace Rogue\Http\Controllers;

use DoSomething\Gateway\Northstar;

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
    }

    /** Index of all users.
     * @return Response
     */
    public function index()
    {
        return 'list of all users';
    }
}
