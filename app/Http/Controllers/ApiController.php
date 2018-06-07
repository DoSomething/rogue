<?php

namespace Rogue\Http\Controllers;

use Rogue\Http\Controllers\Traits\FiltersRequests;
use Rogue\Http\Controllers\Controller as BaseController;

class ApiController extends BaseController
{
    use FiltersRequests;
}
