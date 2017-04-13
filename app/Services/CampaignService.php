<?php

namespace Rogue\Services;

use Rogue\Models\Signup;
use Illuminate\Support\Facades\DB;
use Rogue\Repositories\CacheRepository;

class CampaignService
{
    /**
     * Phoenix instance
     *
     * @var Rogue\Services\Phoenix
     */
    protected $phoenix;

    /**
     * Create new Registrar instance.
     *
     * @param Rogue\Services\Phoenix $phoenix
     */
    public function __construct(Phoenix $phoenix)
    {
        $this->phoenix = $phoenix;
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

        return $campaign['data'];
    }

    /**
     * Finds a group of campagins in Rogue/Phoenix.
     *
     * @param  array $ids
     * @return collection $campaigns|null
     */
    public function findAll(array $ids = [])
    {
        if ($ids) {
            $campaigns = $this->cache->retrieveMany($ids);

            if (! $campaigns) {
                $campaigns = $this->getBatchedCollection($ids);

                if (count($campaigns)) {
                    $group = $campaigns->keyBy('id')->all();

                    $this->cache->storeMany($group);
                }
            } else {
                $campaigns = $this->resolveMissingCampaigns($campaigns);

                $campaigns = collect(array_values($campaigns));
            }

            return $campaigns;
        }

        return null;
    }

    /**
     * Resolving missing cached users in a user cache collection.
     *
     * @param  array $users
     * @return array
     */
    protected function resolveMissingCampaigns($campaigns)
    {
        foreach ($campaigns as $key => $value) {
            if ($value === false or $value === null) {
                $campaigns[$key] = $this->find($key);
            }
        }

        return $campaigns;
    }

    /**
     * Get large number of campaigns from Phoenix.
     *
     * @param  array  $ids
     * @param  int $size
     * @return \Illuminate\Support\Collection
     */
    protected function getBatchedCollection($ids, $size = 50)
    {
        $count = intval(ceil(count($ids) / 50));
        $index = 0;
        $data = [];

        for ($i = 0; $i < $count; $i++) {
            $batch = array_slice($ids, $index, $size);

            $parameters['count'] = '5000';
            $parameters['ids'] = implode(',', $batch);

            $campaigns = $this->phoenix->getAllCampaigns($parameters);

            $data = array_merge($data, $campaigns['data']);

            $index += $size;
        }

        return collect($data);
    }

    /**
     * Group a collection of campaigns by cause space.
     *
     * @param  array  $ids
     * @param  int $size
     * @return \Illuminate\Support\Collection
     */
    public function groupByCause($campaigns)
    {
        $grouped = $campaigns->groupBy(function ($campaign) {
            if ($campaign['staff_pick']) {
                return 'Staff Pick';
            }

            if (! $campaign['causes']['primary']['name']) {
                return 'No Cause';
            }

            $cause = ucfirst($campaign['causes']['primary']['name']);

            return $cause;
        });

        $sorted = $grouped->sortBy(function ($campaigns, $key) {
            // Hacky solution to move the Staff Pick group to the top of the list,
            // the No Cause group to the end of the list,
            // and alphabetize everything inbetween.
            if ($key === 'Staff Pick') {
                return 'A';
            }

            if ($key === 'No Cause') {
                return 'Z';
            }

            return $key;
        });

        return $sorted->toArray();
    }

    /**
     * Get a distinct set of campaign ids from the signups table.
     *
     * @return array $ids
     */
    public function getCampaignIdsFromSignups()
    {
        $campaigns = DB::table('signups')->distinct()->select('campaign_id')->get();

        $ids = collect($campaigns)->pluck('campaign_id')->toArray();

        return $ids ? $ids : null;
    }

    /**
     * Get an aggregate of how many pending, accepted, and reject posts there are for a campaign.
     *
     * @return array $campaign
     */
    public function getCampaignPostStatusCounts($campaign)
    {
        $signups = Signup::campaign($campaign['id'])->includePostStatusCounts()->get();

        $campaign['accepted_count'] = $signups->sum('accepted_count');
        $campaign['pending_count'] = $signups->sum('pending_count');
        $campaign['rejected_count'] = $signups->sum('rejected_count');

        return $campaign;
    }
}
