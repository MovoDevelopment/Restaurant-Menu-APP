<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user/store', [UserController::class, 'store']);
Route::post('user/login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories', 'index');
        Route::post('category/store', 'store');
        Route::post('category/update', 'update');
        Route::post('category/delete', 'destroy');
        Route::get('leaf-categories', 'leafNodes');
    });
    Route::controller(ItemController::class)->group(function () {
        Route::get('items', 'index');
        Route::post('item/store', 'store');
        Route::post('item/delete', 'destroy');
    });
});

