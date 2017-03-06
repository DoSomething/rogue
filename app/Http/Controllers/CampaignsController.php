<?php

namespace Rogue\Http\Controllers;

class CampaignsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');
    }

    /**
     * Show overview of campaigns.
     */
    public function index()
    {
        return view('pages.campaign_overview')
            ->with('state', [
                'message' => 'So what are you gonna say at my funeral, now that you\'ve killed me? Here lies the body of the love of my life, whose heart I broke without a gun to my head. Here lies the mother of my children, both living and dead. Rest in peace, my true love, who I took for granted. Most bomb p*ssy who, because of me, sleep evaded. Her god listening. Her heaven will be a love without betrayal.',
                'subtitle' => 'Ashes to ashes, dust to side chicks.',
            ]);
    }
}
