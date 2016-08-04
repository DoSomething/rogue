<?php

namespace Rogue\Http\Controllers\Api;

use Illuminate\Http\Request;
use Rogue\Models\Reportback;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $reportback = Reportback::create([
        //     'northstar_id' => $request->input('northstar_id'),
        //     'drupal_id' => $request->input('drupal_id'),
        //     'campaign_id' => $request->input('campaign_id'),
        //     'campaign_run_id' => $request->input('campaign_run_id'),
        //     'quantity' => $request->input('quantity'),
        //     'why_participated' => $request->input('why_participated'),
        //     'num_participants' => $request->input('num_participants'),
        //     'flagged' => $request->input('flagged'),
        //     'flagged_reason' => $request->input('flagged_reason'),
        //     'promoted' => $request->input('promoted'),
        //     'promoted_reason' => $request->input('promoted_reason'),
        // ]);
        $this->reportbackService->create($request);

        return $this->item($reportback);
    }
}
