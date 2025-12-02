<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Services\V1\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    public function index(): JsonResponse
    {
        $data = $this->dashboardService->getDashboardData();

        return self::success(message: 'Dashboard data retrieved successfully', data: $data);
    }
}
