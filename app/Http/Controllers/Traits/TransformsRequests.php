<?php

namespace Rogue\Http\Controllers\Traits;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as FractalCollection;

trait TransformsRequests
{
    /**
     * @var \League\Fractal\Manager
     */
    protected $manager;

    /**
     * @var \League\Fractal\TransformerAbstract
     */
    protected $transformer;

    /**
     * Format and return a collection response.
     *
     * @param  object  $data
     * @param  int  $code
     * @param  array  $meta
     * @param  null|object  $transformer
     * @return \Illuminate\Http\JsonResponse
     */
    public function collection($data, $code = 200, $meta = [], $transformer = null)
    {
        $collection = new FractalCollection($data, $this->setTransformer($transformer));

        return $this->transform($collection, $code, $meta);
    }

    /**
     * Format and return a single item response.
     *
     * @param  object  $data
     * @param  int  $code
     * @param  array  $meta
     * @param  null|object  $transformer
     * @param  null|string $include
     * @return \Illuminate\Http\JsonResponse
     */
    public function item($data, $code = 200, $meta = [], $transformer = null, $include = null)
    {
        $item = new Item($data, $this->setTransformer($transformer));

        return $this->transform($item, $code, $meta, $include);
    }

    /**
     * Manage and finalize the data transformation.
     *
     * @param  \League\Fractal\Resource\Item|\League\Fractal\Resource\Collection  $data
     * @param  int  $code
     * @param  array  $meta
     * @param  null|string $include
     * @return \Illuminate\Http\JsonResponse
     */
    public function transform($data, $code = 200, $meta = [], $include = null)
    {
        $data->setMeta($meta);

        $manager = new Manager;

        $manager->setSerializer(new DataArraySerializer);

        if (isset($include)) {
            $manager->parseIncludes($include);
        }

        $response = $manager->createData($data)->toArray();

        return response()->json($response, $code, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Set the Transformer to use otherwise use resource controller default.
     *
     * @param  \League\Fractal\TransformerAbstract|null $transformer
     * @return \League\Fractal\TransformerAbstract
     */
    private function setTransformer($transformer = null)
    {
        if (is_null($transformer)) {
            return $this->transformer;
        }

        return $transformer;
    }

    /**
     * Format & return a paginated collection response.
     *
     * @param $query - Eloquent query
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginatedCollection($query, $request, $code = 200, $meta = [], $transformer = null)
    {
        if (is_null($transformer)) {
            $transformer = $this->transformer;
        }

        // You can request up to 100 items per page (default 20).
        $pages = min((int) $request->query('limit', 20), 100);

        // Is cursor pagination enabled for this route? (Or opted-in using
        // the `?pagination=cursor` query param?)
        $fastMode = ! empty($this->useCursorPagination) || $request->query('pagination') === 'cursor';

        if ($fastMode) {
            $paginator = $query->simplePaginate($pages);
        } else {
            $paginator = $query->paginate($pages);
        }

        $queryParams = array_diff_key($request->query(), array_flip(['page']));
        $paginator->appends($queryParams);

        $resource = new FractalCollection($paginator->getCollection(), $transformer);
        $resource->setMeta($meta);

        // Attach the right paginator or cursor based on "speed".
        if ($fastMode) {
            $cursor = new Cursor(
                $paginator->currentPage(),
                $paginator->previousPageUrl(),
                $paginator->nextPageUrl(),
                $paginator->count()
            );
            $resource->setCursor($cursor);
        } else {
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        }

        $includes = $request->query('include');

        if (is_string($includes)) {
            $includes = explode(',', $request->query('include'));
        }

        return $this->transform($resource, $code, [], $includes);
    }

    /**
     * Return a string as the API response.
     *
     * @param string $message - Message to send in the response
     * @param int $code - Status code
     * @param string $status - The name of the object enclosing the message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond($message, $code = 200, $status = 'success')
    {
        $response = [
            $status => [
                'code' => $code,
                'message' => $message,
            ],
        ];

        return response()->json($response, $code, [], JSON_UNESCAPED_SLASHES);
    }
}
