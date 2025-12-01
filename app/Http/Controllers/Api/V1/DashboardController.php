<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Api\Controller;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return ApiResponse::success('Dashboard data', []);
    }
}
