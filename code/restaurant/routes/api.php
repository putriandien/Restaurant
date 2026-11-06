<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Cashier\OrderController;
use App\Http\Controllers\Cashier\PaymentController;

use App\Http\Controllers\Kitchen\KitchenController as KitchenStaffController;
use App\Http\Controllers\Kitchen\KitchenOrderController;
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:api', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('kitchens', KitchenController::class);
        Route::apiResource('menus', MenuController::class);
        Route::apiResource('ingredients', IngredientController::class);
        Route::apiResource('stocks', StockController::class);
        Route::apiResource('stock-movements', StockMovementController::class);
        Route::apiResource('activity-logs', ActivityLogController::class)
            ->only(['index', 'show', 'destroy']);
        Route::get('dashboard', [DashboardController::class, 'index']);
    });


Route::middleware(['auth:api', 'role:kasir'])
    ->prefix('cashier')
    ->group(function () {
        Route::get('/dashboard', [CashierController::class, 'dashboard']);
        Route::get('/menus', [CashierController::class, 'menus']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders/{order}/payment', [PaymentController::class, 'store']);
    });


Route::middleware(['auth:api', 'role:dapur'])
    ->prefix('kitchen')
    ->group(function () {
        Route::get('/dashboard', [KitchenStaffController::class, 'dashboard']);
        Route::get('/orders', [KitchenStaffController::class, 'orders']);
        Route::get('/orders/{order}', [KitchenStaffController::class, 'show']);
        Route::patch('/orders/{order}/start', [KitchenOrderController::class, 'start']);
        Route::patch('/orders/{order}/complete', [KitchenOrderController::class, 'complete']);
    });