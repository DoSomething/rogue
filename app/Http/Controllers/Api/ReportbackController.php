<?php

namespace Rogue\Http\Controllers\Api;

use Illuminate\Http\Request;

use Rogue\Http\Requests;
use Rogue\Models\Reportback;
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
    public function __construct()
    {
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
        $reportback = Reportback::find(1);
        return $this->item($reportback);
    }
}
