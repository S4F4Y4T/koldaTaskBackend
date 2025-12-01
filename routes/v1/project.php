<?php

use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Support\Facades\Route;

// Project routes - all require authentication
Route::middleware(['jwt.auth', 'throttle:jwt'])->group(function () 
{
    Route::apiResource('projects', ProjectController::class);
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');

    Route::apiResource('tasks', TaskController::class);

});