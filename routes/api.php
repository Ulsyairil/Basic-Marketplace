<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/products', [ProductController::class, 'paginate']);
    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'get']);
        Route::post('/', [ProductController::class, 'store']);
        Route::match(['put', 'patch'], '/', [ProductController::class, 'update']);
        Route::match(['put', 'patch'], '/delete', [ProductController::class, 'delete']);
        Route::match(['put', 'patch'], '/restore', [ProductController::class, 'restore']);
        Route::delete('/', [ProductController::class, 'destroy']);
    });
});
