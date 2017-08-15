<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\Registrar;
use Rogue\Services\CampaignService;

class SignupsController extends Controller
{
    /**
     * Registrar instance
     *
     * @var Rogue\Services\Registrar
     */
    protected $registrar;

    /**
     * Phoenix instance
     *
     * @var Rogue\Services\CampaignService
     */
    protected $campaignService;

    /**
     * Constructor
     *
     * @param Rogue\Services\Registrar $registrar
     * @param Rogue\Services\CampaignService $campaignService
     */
    public function __construct(Registrar $registrar, CampaignService $campaignService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->registrar = $registrar;
        $this->campaignService = $campaignService;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id  signup id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $signup = Signup::find($id);
        $campaign = $this->campaignService->find($id);
        $user = $this->registrar->find($signup->northstar_id);

        return view('signups.show', compact('campaign'))
            ->with('state', [
                'signup' => $signup,
                'campaign' => $campaign,
                'user' => $user->toArray(),
            ]);
    }
}
