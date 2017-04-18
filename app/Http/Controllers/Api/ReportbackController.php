<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Models\Reportback;
use Rogue\Services\ReportbackService;
use Rogue\Http\Requests\ReportbackRequest;
use Rogue\Http\Transformers\ReportbackTransformer;

class ReportbackController extends ApiController
{
    /**
     * @var \Rogue\Http\Transformers\ReportbackTransformer
     */
    protected $transformer;
    protected $itemTransformer;

    /**
     * Create new ReportbackController instance.
     */
    public function __construct(ReportbackService $reportbackService)
    {
        $this->reportbackService = $reportbackService;
        $this->transformer = new ReportbackTransformer;
    }

    /**
     * Returns Post data for the Phoenix-ashes gallery
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Create an empty Post query, which we can filter and paginate
        $query = $this->newQuery(Post::class);

        // 1. Join with signups so we can access the signup data and filter by campaign
        // 2. Only return approved Posts
        // 3. Select all the fields that we will be using
        $query = $query->join('signups', 'signups.id', '=', 'posts.signup_id')
            ->where('status', '=', 'approved')
            ->select('posts.id as id', 'signups.campaign_id as campaign_id', 'posts.status as status', 'posts.caption as caption', 'posts.url as url', 'posts.created_at as created_at', 'posts.signup_id as signup_id');

        $filters = $request->query('filter');

        if (! empty($filters)) {
            // Only return Posts for the given campaign_id (if there is one)
            if (array_has($filters, 'campaign_id')) {
                $query = $query->where('campaign_id', '=', $filters['campaign_id']);
            }

            // Exclude Posts if exclude param is present
            if (array_has($filters, 'exclude')) {
                $query = $query->whereNotIn('posts.id', explode(',', $filters['exclude']));
            }
        }

        // Eagerly load the count of all reactions on a post.
        $query = $query->withCount('reactions');

        // If user param is passed, return whether or not the user has liked the particular post.
        if ($request->query('as_user')) {
            $userId = $request->query('as_user');
            $query = $query->with(['reactions' => function ($query) use ($userId) {
                $query->where('northstar_id', '=', $userId);
            }]);
        }

        return $this->paginatedCollection($query, $request);
    }

    /**
     * Store a newly created resource in storage or
     * update a reportback if it already exists.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportbackRequest $request)
    {
        $transactionId = incrementTransactionId($request);

        // @TODO - instead should probably just have a method that gets northstar_id by default from a drupal_id if that is the only thing provided and then use that to find the reportback.
        $userId = $request['northstar_id'] ? $request['northstar_id'] : $request['drupal_id'];
        $type = $request['northstar_id'] ? 'northstar_id' : 'drupal_id';

        $reportback = $this->reportbackService->getReportback($request['campaign_id'], $request['campaign_run_id'], $userId, $type);

        $updating = ! is_null($reportback);

        if (! $updating) {
            $reportback = $this->reportbackService->create($request->all(), $transactionId);

            $code = 200;
        } else {
            $reportback = $this->reportbackService->update($reportback, $request->all(), $transactionId);

            $code = 201;
        }

        return $this->item($reportback, $code);
    }

    /**
     * Update reportbackitem(s) that already exists.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateReportbackItems(Request $request)
    {
        $this->validate($request, [
            '*.rogue_reportback_item_id' => 'required',
            '*.status' => 'required',
            '*.reviewer' => 'required',
        ]);

        $items = $this->reportbackService->updateReportbackItems($request->all());

        if (empty($items)) {
            $code = 404;
        } else {
            $code = 201;
        }

        $meta = [];

        return $this->collection($items, $code, $meta, $this->itemTransformer);
    }
}
