<?php

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

/*
* Authentication API
*/

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return [
        'name' => $request->user()->name,
        'email' => $request->user()->email,
    ];
});



/*
* Search API
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('search')->group(function () {
        Route::get('/product', [\App\Http\Controllers\SearchController::class, 'productSearch']);
    });
});

/*
* Products API
*/

//Daily limit of 100 requests per 1 minute
Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProductController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\ProductController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\ProductController::class, 'store']);
        Route::post('/{id}', [\App\Http\Controllers\ProductController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\ProductController::class, 'destroy']);
    });
});

/*
* Offers API
*/

//Daily limit of 200 requests per 1 minute
Route::middleware(['auth:sanctum', 'throttle:200,60'])->group(function () {
    Route::prefix('offers')->group(function () {
        Route::get('/', [\App\Http\Controllers\OfferController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\OfferController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\OfferController::class, 'store']);
        Route::post('/{id}', [\App\Http\Controllers\OfferController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\OfferController::class, 'destroy']);
    });
});

/*
* Orders API
*/

//Daily limit of 500 requests per day
Route::middleware(['auth:sanctum', 'throttle:500,1440'])->group(function () {
    Route::prefix('orders')->group(function () {
        Route::get('/', [\App\Http\Controllers\OrderController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\OrderController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\OrderController::class, 'store']);
        Route::post('/{id}', [\App\Http\Controllers\OrderController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\OrderController::class, 'destroy']);
    });
});
