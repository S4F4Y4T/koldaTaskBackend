<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\V1\ModuleResource;
use App\Models\Module;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{

    public function index(): JsonResponse
    {
        $modules = Module::with('permissions')->get();

        return self::success(message: 'Modules retrieved successfully.', data: ModuleResource::collection($modules));
    }
}
