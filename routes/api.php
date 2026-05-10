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
    Route::get('/add_ons', [ApiController::class, 'addOns']);
    Route::get('/vouchers', [ApiController::class, 'vouchers']);
    Route::get('/dashboard', [ApiController::class, 'dashboard']);
    
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
});
