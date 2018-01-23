<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\ReportbackTransformer;
use Rogue\Http\Transformers\PaginatorForPhoenixAshesGallery;

class ReportbackController extends ApiController
{
    /**
     * @var ReportbackTransformer
     */
    protected $transformer;

    /**
     * Create new ReportbackController instance.
     *
     * @param ReportbackTransformer $transformer
     */
    public function __construct(ReportbackTransformer $transformer)
    {
        $this->transformer = $transformer;
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
            ->withoutTag('hide-in-gallery');

        // @TODO: Use `FiltersRequests` trait here!
        $filters = $request->query('filter');
        if (! empty($filters)) {
            // Only return Posts for the given campaign_id (if there is one)
            if (array_has($filters, 'campaign_id')) {
                $query = $query->where('campaign_id', '=', $filters['campaign_id']);
            }

            // Only return Posts for the given northstar_id (if there is one)
            if (array_has($filters, 'northstar_id')) {
                $query = $query->where('northstar_id', '=', $filters['northstar_id']);
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
     * Manage and finalize the data transformation.
     *
     * @param  \League\Fractal\Resource\Item|\League\Fractal\Resource\Collection  $data
     * @param  int  $code
     * @param  array  $meta
     * @return \Illuminate\Http\JsonResponse
     */
    public function transform($data, $code = 200, $meta = [], $include = null, $endpoint = null)
    {
        dd('hihello');
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
