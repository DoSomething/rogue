<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Traits\FiltersRequests;

class ApiController extends BaseController
{
    use FiltersRequests;
}
