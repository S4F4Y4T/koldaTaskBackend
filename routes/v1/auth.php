<?php


use App\Http\Controllers\Api\V1\Authentication\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest', 'throttle:30, 1'])->group(function () {
    Route::post('login', [AuthenticationController::class, 'login'])->name('auth.login');
});

Route::middleware(['jwt.auth', 'throttle:jwt'])->group(function () {
    Route::post('refresh', [AuthenticationController::class, 'refresh'])->name('auth.refresh');
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('auth.logout');
    Route::get('me', [AuthenticationController::class, 'me'])->name('auth.me');
});
