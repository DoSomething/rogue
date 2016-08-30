<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Reportback;
use Illuminate\Http\Request;
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
    public function store(Request $request)
    {
        $userId = $request['northstar_id'] ? $request['northstar_id'] : $request['drupal_id'];
        $type = $request['northstar_id'] ? 'northstar_id' : 'drupal_id';

        $reportback = $this->reportbackService->exists($request['campaign_id'], $request['campaign_run_id'], $userId, $type);

        if (! $reportback) {
            $reportback = $this->reportbackService->create($request->all());

            return $this->item($reportback);
        } else {
            $updatedReportback = $this->reportbackService->update($reportback, $request->all());

            return $this->item($updatedReportback);
        }
    }
}
