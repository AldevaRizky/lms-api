<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\LeaveTypeController;

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

Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('admin')->as('admin.')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('divisions', DivisionController::class);
    Route::apiResource('leave-types', LeaveTypeController::class);
});


