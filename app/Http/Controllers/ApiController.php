<?php

namespace Rogue\Http\Controllers;

use Rogue\Http\Controllers\Controller as BaseController;
use Rogue\Http\Controllers\Traits\FiltersRequests;

class ApiController extends BaseController
{
    use FiltersRequests;
}
