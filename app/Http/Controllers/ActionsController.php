<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Action;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\ActionTransformer;


class ActionsController extends ApiController
{
    /**
     * @var Rogue\Http\Transformers\ActionTransformer;
     */
    protected $transformer;
}
