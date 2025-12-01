<?php


use App\Http\Controllers\Api\V1\Authentication\AuthenticationController;
use App\Http\Middleware\GuestMiddleware;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {

    Route::middleware([GuestMiddleware::class, 'throttle:30, 1'])->group(function () {
        Route::post('login', [AuthenticationController::class, 'login'])->name('auth.login');
        Route::post('forget-password', [AuthenticationController::class, 'forgetPassword'])->name('auth.forgot-password');
        Route::post('reset-password', [AuthenticationController::class, 'resetPassword'])->name('auth.reset-password');
    });

    Route::middleware([JwtMiddleware::class, 'throttle:jwt'])->group(function () {
        Route::post('refresh', [AuthenticationController::class, 'refresh'])->name('auth.refresh');
        Route::post('logout', [AuthenticationController::class, 'logout'])->name('auth.logout');
        Route::get('me', [AuthenticationController::class, 'me'])->name('auth.me');
    });
});
