<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Reportback;
use Rogue\Http\Requests\ReportbackRequest;
use Rogue\Services\ReportbackService;
use Rogue\Http\Transformers\ReportbackTransformer;

class ReportbackController extends ApiController
{
    /**
     * @var \Rogue\Http\Transformers\ReportbackTransformer
     */
    protected $transformer;

    /**
     * Create new ReportbackController instance.
     */
    public function __construct(ReportbackService $reportbackService)
    {
        $this->reportbackService = $reportbackService;
        $this->transformer = new ReportbackTransformer;
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
        // @TODO - instead should probably just have a method that gets northstar_id by default from a drupal_id if that is the only thing provided and then use that to find the reportback.
        $userId = $request['northstar_id'] ? $request['northstar_id'] : $request['drupal_id'];
        $type = $request['northstar_id'] ? 'northstar_id' : 'drupal_id';

        $reportback = $this->reportbackService->getReportback($request['campaign_id'], $request['campaign_run_id'], $userId, $type);

        $updating = ! is_null($reportback);

        if (! $updating) {
            $reportback = $this->reportbackService->create($request->all());

            $code = 200;
        } else {
            $reportback = $this->reportbackService->update($reportback, $request->all());

            $code = 201;
        }

        return $this->item($reportback, $code);
    }
}
