<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\Registrar;
use Rogue\Services\CampaignService;

class CampaignsController extends ApiController
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
        $this->registrar = $registrar;
        $this->campaignService = $campaignService;
    }

    /**
     * Retrieve all campaigns
     */
    public function index(Request $request)
    {
        $ids = [];

        if ($request->query('ids')) {
            $ids = explode(',', $request->query('ids'));
        }

        $campaigns = $this->campaignService->findAll($ids);

        return $campaigns;
    }
}
