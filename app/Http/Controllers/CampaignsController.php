<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Campaign;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\CampaignTransformer;

class CampaignsController extends ApiController
{
    /**
     * @var Rogue\Http\Transformers\CampaignTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->transformer = new CampaignTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Campaign::class);

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Campaign::$indexes);

        // Apply scope for the "computed" is_open field:
        if (isset($filters['is_open'])) {
            // We can use this to find only open campaigns with a truthy filter
            // applied, e.g. `?filter[is_open]=true`, or closed campaigns with
            // a falsy filter, e.g. `?filter[is_open]=false`.
            if (filter_var($filters['is_open'], FILTER_VALIDATE_BOOLEAN)) {
                $query->whereOpen();
            } else {
                $query->whereClosed();
            }
        }

        // Attach counts for total posts & pending posts:
        $includePendingCount = str_contains($request->query('include'), 'pending_count')
            || str_contains($request->query('orderBy'), 'pending_count');
        if ($includePendingCount && is_staff_user()) {
            $query->withPendingPostCount();
        }

        // Experimental: Allow paginating by cursor (e.g. `?after=OTAxNg==`):
        if ($cursor = $request->query('after')) {
            $query->whereAfterCursor($cursor);

            // Using 'after' implies cursor pagination:
            $this->useCursorPagination = true;
        }

        // Allow ordering results:
        $orderBy = $request->query('orderBy');
        $query = $this->orderBy($query, $orderBy, ['id', 'pending_count']);

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rogue\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $query = $this->newQuery(Campaign::class);

        $includePendingCount = str_contains($request->query('include'), 'pending_count');
        if ($includePendingCount && is_staff_user()) {
            $query->withPendingPostCount();
        }

        $campaign = $query->findOrFail($id);

        return $this->item($campaign);
    }
}
