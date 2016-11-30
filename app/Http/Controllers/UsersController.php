<?php

namespace Rogue\Http\Controllers;

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
     * List of Rogue users.
     */
    public function index()
    {

    }
}
