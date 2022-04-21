<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\CategoryController;

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

Route::controller(UserController::class)->prefix('users')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::get('show', 'find');
    Route::put('', 'updateInfo');
    Route::put('change-password', 'updatePassword');
    Route::delete('', 'delete');
});

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::get('show', 'find');
    Route::put('', 'update');
    Route::delete('', 'delete');
});
