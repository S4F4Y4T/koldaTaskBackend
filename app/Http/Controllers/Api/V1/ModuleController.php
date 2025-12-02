<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\V1\ModuleResource;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use App\Models\Role;

class ModuleController extends Controller
{
    protected string $policyModel = Role::class;

    public function index(): JsonResponse
    {
        $this->isAuthorized('all');

        $modules = Module::with('permissions')->get();

        return self::success(message: 'Modules retrieved successfully.', data: ModuleResource::collection($modules));
    }
}
