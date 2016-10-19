<?php

namespace Rogue\Http\Controllers;

class ReportbacksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');
    }

    /**
     * For now, just a test to have route to send authenticated/logged out users to.
     */
    public function index()
    {
        return view('reportbacks.index');
    }
}
