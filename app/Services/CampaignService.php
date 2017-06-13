<?php

namespace Rogue\Services;

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

            $campaign = $campaign['data'];
        }

        return $campaign;
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

            return collect($campaigns);
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
     * Get Post totals for a collection of campaigns or a single campaign.
     *
     * @return Illuminate\Support\Collection| object $campaigns
     */
    public function getPostTotals($campaigns)
    {
        if ($campaigns instanceof \Illuminate\Support\Collection) {
            return $this->getCollectionOfCampaignsPostTotals($campaigns);
        }

        if (is_array($campaigns)) {
            return $this->getSingleCampaignPostTotals($campaigns);
        }

        return null;
    }

    /**
     * Gets the count of pending, accepted, and rejected stautses on each post for a collection of campaigns.
     *
     * @param  Illuminate\Support\Collection $campaigns
     * @return Illuminate\Support\Collection $campaigns
     */
    public function getCollectionOfCampaignsPostTotals($campaigns)
    {
        $ids = $campaigns->pluck('id')->filter()->toArray();

        $totals = DB::table('signups')
                ->leftJoin('posts', 'signups.id', '=', 'posts.signup_id')
                ->select('signups.campaign_id',
                    DB::raw('SUM(case when posts.status = "accepted" then 1 else 0 end) as accepted_count'),
                    DB::raw('SUM(case when posts.status = "pending" then 1 else 0 end) as pending_count'),
                    DB::raw('SUM(case when posts.status = "rejected" then 1 else 0 end) as rejected_count'))
                ->wherein('campaign_id', $ids)
                ->groupBy('signups.campaign_id')
                ->get();

        return $totals ? collect($totals)->keyBy('campaign_id') : collect();
    }

    /**
     * Gets the count of pending, accepted, and rejected stautses on each post for a single campaign.
     *
     * @param  array $campaign
     * @return array $toals | null
     */
    public function getSingleCampaignPostTotals($campaign)
    {
        return DB::table('signups')
                ->leftJoin('posts', 'signups.id', '=', 'posts.signup_id')
                ->select('signups.campaign_id',
                    DB::raw('SUM(case when posts.status = "accepted" then 1 else 0 end) as accepted_count'),
                    DB::raw('SUM(case when posts.status = "pending" then 1 else 0 end) as pending_count'),
                    DB::raw('SUM(case when posts.status = "rejected" then 1 else 0 end) as rejected_count'))
                ->where('campaign_id', '=', $campaign['id'])
                ->groupBy('signups.campaign_id')
                ->first();
    }

    /**
     * Appends status counts to a collection of campaigns.
     *
     * @param  array $campaigns
     * @return Illuminate\Support\Collection $campaigns | null
     */
    public function appendStatusCountsToCampaigns($campaigns)
    {
        $campaignsWithCounts = $this->getPostTotals($campaigns);

        if ($campaignsWithCounts) {
            $campaigns = $campaigns->map(function ($campaign, $key) use ($campaignsWithCounts) {
                if ($campaign) {
                    $statusCounts = $campaignsWithCounts->get($campaign['id']);

                    if ($statusCounts) {
                        $campaign['accepted_count'] = (int) $statusCounts->accepted_count;
                        $campaign['pending_count'] = (int) $statusCounts->pending_count;
                        $campaign['rejected_count'] = (int) $statusCounts->rejected_count;
                    }
                }

                return $campaign;
            });

            return $campaigns;
        }

        return null;
    }
}
