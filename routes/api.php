<?php

use App\Http\Controllers\CategoryController;
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
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('category/store', [CategoryController::class, 'store']);
    Route::post('category/update', [CategoryController::class, 'update']);
    Route::get('leaf-categories', [CategoryController::class, 'leafNodes']);
});

