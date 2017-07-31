<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use Rogue\Models\Reportback;
use Rogue\Services\ReportbackService;
use Rogue\Http\Requests\ReportbackRequest;
use Rogue\Http\Transformers\ReportbackTransformer;
use Rogue\Http\Transformers\PaginatorForPhoenixAshesGallery;

class ReportbackController extends ApiController
{
    /**
     * The Reportback service.
     *
     * @var ReportbackService
     */
    protected $reportbackService;

    /**
     * @var ReportbackTransformer
     */
    protected $transformer;

    /**
     * Create new ReportbackController instance.
     *
     * @param ReportbackService $reportbackService
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
        $query = $this->newQuery(Post::class)
            // Eagerly load the `signup` relationship.
            ->with('signup')
            // And eagerly load the count of reactions per post.
            ->withCount('reactions')
            // Only return posts that have been approved by a campaign lead...
            ->where('status', 'accepted')
            // ...and haven't been hidden from the gallery.
            ->withoutTags(['Hide In Gallery']);
        $filters = $request->query('filter');
        if (! empty($filters)) {
            // Only return Posts for the given campaign_id (if there is one)
            if (array_has($filters, 'campaign_id')) {
                $query = $query->where('campaign_id', '=', $filters['campaign_id']);
            }

            // Only return Posts for the given northstar_id (if there is one)
            if (array_has($filters, 'northstar_id')) {
                $query = $query->where('signups.northstar_id', '=', $filters['northstar_id']);
            }

            // Only return Posts for the given status (if there is one)
            if (array_has($filters, 'status')) {
                $query = $query->where('status', '=', $filters['status']);
            }

            // Exclude Posts if exclude param is present
            if (array_has($filters, 'exclude')) {
                $query = $query->whereNotIn('id', explode(',', $filters['exclude']));
            }
        }

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

    /**
     * Manage and finalize the data transformation.
     *
     * @param  \League\Fractal\Resource\Item|\League\Fractal\Resource\Collection  $data
     * @param  int  $code
     * @param  array  $meta
     * @return \Illuminate\Http\JsonResponse
     */
    public function transform($data, $code = 200, $meta = [], $include = null, $endpoint = null)
    {
        $data->setMeta($meta);

        $manager = new Manager;

        $manager->setSerializer(new PaginatorForPhoenixAshesGallery);

        if (isset($include)) {
            $manager->parseIncludes($include);
        }

        $response = $manager->createData($data)->toArray();

        return response()->json($response, $code, [], JSON_UNESCAPED_SLASHES);
    }
}
