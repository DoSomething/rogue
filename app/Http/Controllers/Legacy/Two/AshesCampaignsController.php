<?php

namespace Rogue\Http\Controllers\Legacy\Two;

use Illuminate\Http\Request;
use Rogue\Services\CampaignService;

class AshesCampaignsController extends ApiController
{
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
    public function __construct(CampaignService $campaignService)
    {
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
