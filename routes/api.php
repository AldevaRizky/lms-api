<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Admin\LeaveBalanceController;
use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\Admin\CompanySettingController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::patch('auth/{userid}', [AuthController::class, 'update']);
    Route::get('auth/{userid}', [AuthController::class, 'show']);
    Route::delete('auth/{userid}', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('divisions', DivisionController::class);
    Route::apiResource('leave-types', LeaveTypeController::class);
    Route::apiResource('leave-balances', LeaveBalanceController::class);
    Route::apiResource('leave-requests', LeaveRequestController::class);
    Route::apiResource('company-settings', CompanySettingController::class);
});


