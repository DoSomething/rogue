<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Models\Signup;
use Rogue\Models\Campaign;
use Rogue\Services\Registrar;
use Rogue\Http\Controllers\Controller;

class SignupsController extends Controller
{
    /**
     * Registrar instance
     *
     * @var Rogue\Services\Registrar
     */
    protected $registrar;

    /**
     * Constructor
     *
     * @param Rogue\Services\Registrar $registrar
     */
    public function __construct(Registrar $registrar)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');
        $this->registrar = $registrar;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id  signup id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $signup = Signup::with('posts')->findOrFail($id);
        $campaign = Campaign::find($signup->campaign_id);
        $user = $this->registrar->find($signup->northstar_id);

        return view('signups.show', compact('campaign'))
            ->with('state', [
                'signup_id' => $signup->id,
                // @TODO - We could probably grab campaign and user info from API as well.
                'campaign' => $campaign,
                'user' => $user->toArray(),
            ]);
    }
}
