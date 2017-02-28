<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Services\PostService;
use Rogue\Services\ReportbackService;
use Rogue\Http\Transformers\PhotoTransformer;
use Rogue\Http\Transformers\ReportbackItemTransformer;

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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews(Request $request)
    {
        $this->validate($request, [
            '*.rogue_event_id' => 'required',
            '*.status' => 'required',
            '*.reviewer' => 'required',
        ]);

        $reportbackItems = [];
        $photos = [];

        // Loop through the $request and separate reportback items from photos.
        foreach ($request->all() as $review) {
            $post = Post::where(['event_id' => $review['rogue_event_id']])->first();
            if ($post) {
                array_push($photos, $review);
            } else {
                $review['rogue_reportback_item_id'] = $review['rogue_event_id'];
                array_push($reportbackItems, array_except($review, ['rogue_event_id']));
            }
        }

        if ($reportbackItems) {
            $reviewedReportbackItems = $this->reportbacks->updateReportbackItems($reportbackItems);
            $reviewedReportbackItemsCode = $this->code($reviewedReportbackItems);
        }

        if ($photos) {
            $reviewedPhotos = $this->posts->reviews($photos);
            $reviewedPhotosCode = $this->code($reviewedPhotos);
        }

        $meta = [];

        if (isset($reviewedReportbackItems)) {
            return $this->collection($reviewedReportbackItems, $reviewedReportbackItemsCode, $meta, $this->reportbackItemTransformer);
        } elseif (isset($reviewedPhotos)) {
            return $this->collection($reviewedPhotos, $reviewedPhotosCode, $meta, $this->photoTransformer);
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
