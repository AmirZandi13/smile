<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'v1'], function () {
    Route::get('accounts', [AccountController::class, 'index']);
    Route::post('accounts', [AccountController::class, 'store']);
    Route::post('transfer', [AccountController::class, 'transfer']);
    Route::get('balance', [AccountController::class, 'balance']);
    Route::get('transactions', [TransactionController::class, 'index']);
});
