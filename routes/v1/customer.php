<?php

use App\Http\Controllers\Api\V1\Customer\Auth\CustomerAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Cart\CartController;

Route::group(['prefix' => 'customer'], function () {

    Route::post('register', [CustomerAuthController::class, 'register'])->name('customer.auth.register');
    Route::post('login', [CustomerAuthController::class, 'login'])->name('customer.auth.login');
    Route::post('verify-email', [CustomerAuthController::class, 'verifyEmail'])->name('customer.auth.verify-email');
    Route::post('resend-verification', [CustomerAuthController::class, 'resendVerification'])->name('customer.auth.resend-verification');
    Route::post('forgot-password', [CustomerAuthController::class, 'forgotPassword'])->name('customer.auth.forgot-password');
    Route::post('reset-password', [CustomerAuthController::class, 'resetPassword'])->name('customer.auth.reset-password');

    Route::middleware(['customer.auth'])->group(function () {
        Route::post('logout', [CustomerAuthController::class, 'logout'])->name('customer.auth.logout');
        Route::post('refresh', [CustomerAuthController::class, 'refresh'])->name('customer.auth.refresh');
        Route::get('me', [CustomerAuthController::class, 'me'])->name('customer.auth.me');
        Route::put('me', [CustomerAuthController::class, 'updateProfile'])->name('customer.auth.update-profile');

        // Billing Address Management
        Route::get('addresses/billing', [\App\Http\Controllers\Api\V1\Customer\BillingAddressController::class, 'show'])->name('customer.addresses.billing.show');
        Route::post('addresses/billing', [\App\Http\Controllers\Api\V1\Customer\BillingAddressController::class, 'store'])->name('customer.addresses.billing.store');

        // Shipping Address Management
        Route::apiResource('addresses/shipping', \App\Http\Controllers\Api\V1\Customer\ShippingAddressController::class)->names([
            'index' => 'customer.addresses.shipping.index',
            'store' => 'customer.addresses.shipping.store',
            'show' => 'customer.addresses.shipping.show',
            'update' => 'customer.addresses.shipping.update',
            'destroy' => 'customer.addresses.shipping.destroy',
        ]);

        // Order Management (CheckoutController)
        Route::get('orders', [\App\Http\Controllers\Api\V1\Order\CheckoutController::class, 'index'])->name('customer.orders.index');
        Route::get('orders/{id}', [\App\Http\Controllers\Api\V1\Order\CheckoutController::class, 'show'])->name('customer.orders.show');
        Route::post('checkout', [\App\Http\Controllers\Api\V1\Order\CheckoutController::class, 'checkout'])->name('customer.orders.store');
        Route::post('orders/{id}/cancel', [\App\Http\Controllers\Api\V1\Order\CheckoutController::class, 'cancel'])->name('customer.orders.cancel');

    });
});

Route::middleware(['customer.auth'])->group(function () {

        // Cart endpoints (RESTful resource + clear action)
        Route::delete('cart/clear', [CartController::class, 'clear'])->name('cart.clear')->middleware('validate.cart.clear.auth');
        Route::apiResource('cart', CartController::class)->except(['show'])->middleware('validate.cart.ownership');
    });
