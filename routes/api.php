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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['data.transform'])->group(function () {
    Route::apiResource('policy', App\Http\Controllers\PolicyController::class);
    Route::apiResource('rule', App\Http\Controllers\RuleController::class);
    Route::apiResource('log', App\Http\Controllers\LogController::class);
    Route::post('logs', [App\Http\Controllers\LogController::class, 'paginate']);
});

Route::post('validate', [App\Http\Controllers\ValidateController::class, 'post']);
Route::post('test', [App\Http\Controllers\ValidateController::class, 'test']);
