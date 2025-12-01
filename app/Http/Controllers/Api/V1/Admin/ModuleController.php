<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
    public function index(): JsonResponse
    {
        // You might want to add authorization here if needed, e.g., only admins can see modules
        // $this->isAuthorized('viewAny', Role::class); 

        return self::success(message: 'Modules fetched successfully', data: PermissionEnum::modules());
    }
}
