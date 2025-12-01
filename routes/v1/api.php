<?php

use App\Http\Controllers\Api\V1\Animal\AnimalController;
use App\Http\Controllers\Api\V1\Animal\AnimalExpenseController;
use App\Http\Controllers\Api\V1\Animal\AnimalPartController;
use App\Http\Controllers\Api\V1\Animal\AnimalReportController;
use App\Http\Controllers\Api\V1\Animal\ExpenseController;
use App\Http\Controllers\Api\V1\Authentication\PermissionController;
use App\Http\Controllers\Api\V1\Authentication\RoleController;
use App\Http\Controllers\Api\V1\Authentication\UserController;
use App\Http\Controllers\Api\V1\Breed\BreedController;
use App\Http\Controllers\Api\V1\Customer\CustomerController;
use App\Http\Controllers\Api\V1\Dashboard\DashboardController;
use App\Http\Controllers\Api\V1\Finance\AccountController;
use App\Http\Controllers\Api\V1\Finance\TransactionCategoryController;
use App\Http\Controllers\Api\V1\Finance\TransactionController;
use App\Http\Controllers\Api\V1\Growth\GrowthPolicyController;
use App\Http\Controllers\Api\V1\Growth\GrowthRateController;
use App\Http\Controllers\Api\V1\Growth\NutrientLogController;
use App\Http\Controllers\Api\V1\Growth\WeightController;
use App\Http\Controllers\Api\V1\Hrms\PayslipController;
use App\Http\Controllers\Api\V1\Hrms\StaffAnimalAssignmentController;
use App\Http\Controllers\Api\V1\Hrms\StaffController;
use App\Http\Controllers\Api\V1\Hrms\StaffPayslipController;
use App\Http\Controllers\Api\V1\Item\EquipmentController;
use App\Http\Controllers\Api\V1\Item\FoodIngredientController;
use App\Http\Controllers\Api\V1\Item\FoodItemController;
use App\Http\Controllers\Api\V1\Item\ItemCategoryController;
use App\Http\Controllers\Api\V1\Item\VeterinaryItemController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\Nutrient\NutrientController;
use App\Http\Controllers\Api\V1\Production\FoodProductionController;
use App\Http\Controllers\Api\V1\Production\RecipeController;
use App\Http\Controllers\Api\V1\Provision\ProvisionController;
use App\Http\Controllers\Api\V1\Purchase\PurchaseController;
use App\Http\Controllers\Api\V1\Quarantine\QuarantineController;
use App\Http\Controllers\Api\V1\ScheduledAction\ScheduledActionController;
use App\Http\Controllers\Api\V1\ScheduledAction\ScheduledActionLogController;
use App\Http\Controllers\Api\V1\Sell\SellController;
use App\Http\Controllers\Api\V1\Slaughter\SlaughterController;
use App\Http\Controllers\Api\V1\Stock\AnimalPartStockController;
use App\Http\Controllers\Api\V1\Stock\ItemStockController;
use App\Http\Controllers\Api\V1\Stock\ProductionStockController;
use App\Http\Controllers\Api\V1\Stock\StorageController;
use App\Http\Controllers\Api\V1\Supplier\SupplierController;
use App\Http\Controllers\Api\V1\Unit\UnitController;
use App\Http\Controllers\Api\V1\Zone\ZoneController;
use App\Traits\V1\ApiResponse;
use Illuminate\Support\Facades\Route;

//http://127.0.0.1:8000/api/v1/users?sort=-id&includes=roles&show=2&page=3&filter['name']=safayat

// ==================== AUTHENTICATION & CUSTOMER ROUTES ====================
require __DIR__ . '/auth.php';

Route::middleware(['jwt.auth', 'throttle:jwt', 'throttle:60,1'])->group(function ()
{

    //users
    Route::apiResource('users', UserController::class);
    
    // RBAC & Modules       
    Route::apiResource('roles', \App\Http\Controllers\Api\V1\Admin\RoleController::class);
    Route::post('roles/{role}/permissions/assign', [\App\Http\Controllers\Api\V1\Admin\RoleController::class, 'assignPermissions']);
    Route::get('modules', [\App\Http\Controllers\Api\V1\Admin\ModuleController::class, 'index']);

    //dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
});

// ==================== HEALTH CHECK ====================
Route::get('/health', function () {
    return ApiResponse::success(message:'Healthy', data:[
        'timestamp' => now(),
    ]);
})->middleware('throttle:2,1')->name('health');
