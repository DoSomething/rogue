<?php

namespace Rogue\Http\Controllers;

use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Instantiate a new ImportController instance.
     *
     * @param Rogue\Services\Registrar $registrar
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     */
    public function show()
    {
        return view('pages.upload');
    }
}
