<?php

use Illuminate\Support\Facades\Route;
use Dedoc\Scramble\Http\Middleware\RestrictedDocsAccess;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([RestrictedDocsAccess::class])
    ->group(function () {
        Route::get('/docs/api', function () {
            return view('vendor.scramble.index');
        });
    });