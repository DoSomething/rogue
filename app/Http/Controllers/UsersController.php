<?php

namespace Rogue\Http\Controllers;

// use DoSomething\Gateway\Northstar;
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
        // $this->northstar = $northstar;
        $this->registrar = $registrar;
    }

    /**
     * For now, just a test to have route to send authenticated/logged out users to.
     */
    public function index()
    {

        dd($this->registrar->find('559442cca59dbfc9578b4bf4'));
        // dd($this->registrar);
    }
}
