<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\V1\ModuleResource;
use App\Models\Module;
use App\Traits\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $modules = Module::with('permissions')->get();

        return self::success('Modules retrieved successfully.', ModuleResource::collection($modules));
    }
}
