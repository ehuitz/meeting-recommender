<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SyncController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResource('busies', \App\Http\Controllers\Api\BusyController::class);

    Route::post('/syncs', [SyncController::class, 'upload'])->name('syncs');
    Route::get('employees', [\App\Http\Controllers\Api\EmployeeController::class, 'index']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });
});

Route::apiResource('frees', \App\Http\Controllers\Api\FreeController::class)->only(['index']);




