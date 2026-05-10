<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\OrderController;

Route::post('/login', [ApiController::class, 'login']);

// Public routes for catalog (Mobile App Sync)
Route::get('/services', [ApiController::class, 'services']);
Route::get('/add_ons', [ApiController::class, 'addOns']);
Route::post('/add_ons', [ApiController::class, 'storeAddOn']);
Route::post('/orders', [OrderController::class, 'store']); // Public order creation

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [ApiController::class, 'logout']);
    
    Route::get('/vouchers', [ApiController::class, 'vouchers']);
    Route::get('/dashboard', [ApiController::class, 'dashboard']);
});
