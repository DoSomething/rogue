<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Signup;
use Illuminate\Http\Request;

class SignupsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  string  $id  signup id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "signup page";
    }
}
