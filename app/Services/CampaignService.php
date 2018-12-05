<?php

namespace Rogue\Services;

use Rogue\Models\Campaign;
use Illuminate\Support\Facades\DB;
use Rogue\Repositories\CacheRepository;

class CampaignService
{
    /**
     * Ashes instance
     *
     * @var \Rogue\Services\Ashes
     */
    protected $ashes;

    /**
     * Phoenix instance
     *
     * @var \Rogue\Services\Phoenix
     */
    protected $phoenix;

    /**
     * The cache repository.
     *
     * @var CacheRepository
     */
    protected $cache;

    /**
     * Create new Registrar instance.
     *
     * @param Ashes $ashes
     */
    public function __construct(Ashes $ashes, Phoenix $phoenix)
    {
        $this->ashes = $ashes;
        $this->phoenix = $phoenix;
        $this->cache = new CacheRepository('campaign');
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
        /* // Handle cases where the ID was not found. */
        /* if (empty($campaign['data']['id'])) { */
        /*     $placeholder = [ */
        /*         'id' => $id, */
        /*         'title' => 'Unknown Campaign (' . $id . ')', */
        /*         'staff_pick' => false, */
        /*         'causes' => [ */
        /*             'primary' => [ */
        /*                 'name' => null, */
        /*             ], */
        /*         ], */
        /*         'uri' => null, */
        /*     ]; */

        return Campaign::find($id);
    }

    /**
     * Finds a group of campaigns in Rogue/Ashes.
     *
     * @param  array $ids
     * @return \Illuminate\Support\Collection
     */
    public function findAll($ids = [])
    {
        return $ids ? Campaign::find($ids) : Campaign::all();
    }

    /**
     * Resolving missing cached users in a user cache collection.
     *
     * @param  array $campaigns
     * @return array
     */
    protected function resolveMissingCampaigns($campaigns)
    {
        foreach ($campaigns as $key => $value) {
            if ($value === false or $value === null) {
                $key = $this->cache->unsetPrefix($key);
                $campaigns[$key] = $this->find($key);
            }
        }

        return $campaigns;
    }

    /**
     * Get large number of campaigns from Ashes.
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

            $campaigns = $this->ashes->getAllCampaigns($parameters);

            $data = array_merge($data, $campaigns['data']);

            $index += $size;
        }

        return collect($data);
    }

    /**
     * Group a collection of campaigns by cause space.
     *
     * @param  array $campaigns
     * @return \Illuminate\Support\Collection
     */
    public function groupByCause($campaigns)
    {
        return $campaigns->groupBy('cause')->toArray();
    }

    /**
     * Get a distinct set of campaign ids from the signups table.
     *
     * @return array|null
     */
    public function getCampaignIdsFromSignups()
    {
        $campaigns = DB::table('signups')->distinct()->select('campaign_id')->get();

        $ids = collect($campaigns)->pluck('campaign_id')->toArray();

        return $ids ? $ids : null;
    }

    /**
     * Gets the count of pending, accepted, and rejected stautses on each post for a single campaign.
     *
     * @param  array $campaign
     * @return \Illuminate\Support\Collection
     */
    public function getPostTotals($campaign)
    {
        return DB::table('signups')
                ->leftJoin('posts', 'signups.id', '=', 'posts.signup_id')
                ->select('signups.campaign_id',
                    DB::raw('SUM(case when posts.status = "accepted" && posts.deleted_at is null then 1 else 0 end) as accepted_count'),
                    DB::raw('SUM(case when posts.status = "pending" && posts.deleted_at is null then 1 else 0 end) as pending_count'),
                    DB::raw('SUM(case when posts.status = "rejected" && posts.deleted_at is null then 1 else 0 end) as rejected_count'))
                ->where('signups.campaign_id', '=', $campaign['id'])
                ->groupBy('signups.campaign_id')
                ->first();
    }

    /**
     * Gets the count of pending stautses on each post for a collection of campaigns.
     *
     * @param  \Illuminate\Support\Collection $campaigns
     * @return \Illuminate\Support\Collection
     */
    public function getPendingPostTotals($campaigns)
    {
        $ids = $campaigns->pluck('id')->filter()->toArray();

        $totals = DB::table('signups')
                ->leftJoin('posts', 'signups.id', '=', 'posts.signup_id')
                ->selectRaw('signups.campaign_id, count(posts.id) as pending_count')
                ->where('status', '=', 'pending')
                ->where('posts.deleted_at', '=', null)
                ->wherein('signups.campaign_id', $ids)
                ->groupBy('signups.campaign_id')
                ->get();

        return $totals ? collect($totals)->keyBy('campaign_id') : collect();
    }

    /**
     * Appends count of pending posts to a collection of campaigns.
     *
     * @param  array $campaigns
     * @return \Illuminate\Support\Collection|null
     */
    public function appendPendingCountsToCampaigns($campaigns)
    {
        $campaignsWithCounts = $this->getPendingPostTotals($campaigns);

        if ($campaignsWithCounts) {
            $campaigns = $campaigns->map(function ($campaign, $key) use ($campaignsWithCounts) {
                if ($campaign) {
                    $statusCounts = $campaignsWithCounts->get($campaign['id']);

                    $campaign['pending_count'] = $statusCounts ? (int) $statusCounts->pending_count : 0;
                }

                return $campaign;
            });

            return $campaigns;
        }

        return null;
    }

    /**
     * Appends status counts to a collection of campaigns.
     *
     * @param  array $campaigns
     * @return \Illuminate\Support\Collection|null
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
