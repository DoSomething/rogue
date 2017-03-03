<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use Rogue\Services\PostService;
use Rogue\Services\ReportbackService;
use Rogue\Http\Transformers\PhotoTransformer;
use Rogue\Http\Transformers\ReportbackItemTransformer;
use Rogue\Http\Requests\ReviewsRequest;

class ReviewsController extends ApiController
{
    /**
     * The photo service instance.
     *
     * @var Rogue\Services\PostService
     */
    protected $posts;

    /**
     * The reportback service instance.
     *
     * @var Rogue\Services\ReportbackService
     */
    protected $reportbacks;

    /**
     * @var \Rogue\Http\Transformers\ReportbackItemTransformer
     */
    protected $reportbackItemTransformer;

    /**
     * @var \Rogue\Http\Transformers\PhotoTransformer
     */
    protected $photoTransformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(PostService $posts, ReportbackService $reportbacks)
    {
        $this->middleware('api');

        $this->posts = $posts;
        $this->reportbacks = $reportbacks;

        $this->reportbackItemTransformer = new ReportbackItemTransformer;
        $this->photoTransformer = new PhotoTransformer;
    }

    /**
     * Update a post(s)'s status when reviewed.
     *
     * @param Rogue\Http\Requests\ReviewsRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews(ReviewsRequest $request)
    {
        $reviewedPhoto = $this->posts->reviews($request->all());
        $reviewedPhotoCode = $this->code($reviewedPhoto);

        $meta = [];

        if (isset($reviewedPhoto)) {
            return $this->item($reviewedPhoto, $reviewedPhotoCode, $meta, $this->photoTransformer);
        } else {
            return 404;
        }
    }

    /**
     * Determine status code.
     *
     * @param array $reviewed
     *
     * @return int $code
     */
    public function code($reviewed)
    {
        if (empty($reviewed)) {
            return 404;
        } else {
            return 201;
        }
    }
}
