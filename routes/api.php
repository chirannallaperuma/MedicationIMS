<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function () {

    // authentication route
    Route::post('register', [\App\Http\Controllers\Frontend\FrontuserAuthController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\Frontend\FrontuserAuthController::class, 'login']);

    // customer routes
    Route::group(['middleware' => ['auth:sanctum', 'role:owner,manager,cashier']], function () {
        Route::post('customers', [\App\Http\Controllers\Frontend\CustomerController::class, 'create']);
        Route::get('customers', [\App\Http\Controllers\Frontend\CustomerController::class, 'index']);
        Route::put('customers/{id}', [\App\Http\Controllers\Frontend\CustomerController::class, 'update']);
        Route::delete('customers/{id}', [\App\Http\Controllers\Frontend\CustomerController::class, 'destroy']);
    });

    // medications routes
    Route::group(['middleware' => ['auth:sanctum', 'role:owner,manager,cashier']], function () {
        Route::post('medications', [\App\Http\Controllers\Frontend\MedicationController::class, 'create']);
        Route::get('medications', [\App\Http\Controllers\Frontend\MedicationController::class, 'index']);
        Route::put('medications/{id}', [\App\Http\Controllers\Frontend\MedicationController::class, 'update']);
        Route::delete('medications/{id}', [\App\Http\Controllers\Frontend\MedicationController::class, 'destroy']);
    });
});
