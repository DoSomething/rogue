<?php

namespace Rogue\Http\Controllers\Legacy\Web;

use Rogue\Http\Controllers\Controller;

class PagesController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->only('home');
    }

    /**
     * Display the Rogue homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Display the FAQ page.
     *
     * @return \Illuminate\Http\Response
     */
    public function faq()
    {
        return view('pages.faq');
    }
}
