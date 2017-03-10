<?php

namespace Rogue\Services;

use Rogue\Services\Phoenix;
use Rogue\Repositories\CacheRepository;
use Illuminate\Support\Facades\DB;

class CampaignService
{
    /**
     * Create new Registrar instance.
     */
    public function __construct()
    {
        $this->phoenix = new Phoenix;
        $this->cache = new CacheRepository;
    }

    /**
     * Finds a single campaign in Rogue. If the campaign is not found
     * in the Cache, then we grab it directly from Phoenix and store in cache.
     *
     * @param  string $id
     * @return object $user Northstar user object
     */
    public function find($id)
    {
        $campaign = $this->cache->retrieve($id);

        if (! $campaign) {
            $campaign = $this->phoenix->getCampaign($id);

            $this->cache->store($campaign['data']['id'], $campaign['data']);
        }

        return $campaign;
    }

    /**
     * Returns an array of unique campaign IDs that we have signups for.
     *
     * @return array $ids
     */
    public function getAllCampaigns()
    {
        $campaigns = DB::table('signups')->select('campaign_id')->groupBy('campaign_id')->get();

        $ids = collect($campaigns)->pluck('campaign_id')->toArray();

        return $ids;
    }
}
