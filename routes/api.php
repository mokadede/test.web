<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\OrderController;

Route::post('/login', [ApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [ApiController::class, 'logout']);
    
    Route::get('/services', [ApiController::class, 'services']);
    Route::post('/services', [ApiController::class, 'storeService']);
    Route::put('/services/{id}', [ApiController::class, 'updateService']);
    Route::delete('/services/{id}', [ApiController::class, 'deleteService']);

    Route::get('/add_ons', [ApiController::class, 'addOns']);
    Route::post('/add_ons', [ApiController::class, 'storeAddOn']);
    Route::delete('/add_ons/{id}', [ApiController::class, 'deleteAddOn']);

    Route::get('/vouchers', [ApiController::class, 'vouchers']);
    Route::get('/dashboard', [ApiController::class, 'dashboard']);
    
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
});
