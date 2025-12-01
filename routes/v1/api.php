<?php

use App\Traits\V1\ApiResponse;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\DashboardController;


require __DIR__ . '/auth.php';
require __DIR__ . '/project.php';

Route::middleware(['jwt.auth', 'throttle:jwt'])->group(function () 
{
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::apiResource('users', UserController::class);
    Route::get('modules', [ModuleController::class, 'index']);
    Route::apiResource('roles', RoleController::class);
    Route::post('roles/{role}/permissions/assign', [RoleController::class, 'assignPermissions']);
});

Route::get('/health', function () {
    return ApiResponse::success(message: 'Healthy', data: [
        'timestamp' => now(),
    ]);
})->middleware('throttle:2,1')->name('health');
