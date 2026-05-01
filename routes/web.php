<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public tracking page (no login required)
Route::get('/track', [TrackingController::class, 'index'])->name('track');
Route::post('/track', [TrackingController::class, 'search'])->name('track.search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (in_array(auth()->user()->role, ['owner', 'karyawan'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    Route::get('/customer/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    Route::middleware('is_admin')->group(function () {
        // Redirection for admin dashboard
        Route::get('/admin/dashboard', function () {
            return redirect()->route('admin.orders');
        })->name('admin.dashboard');

        // Karyawan & Owner
        Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');

        // Owner Only
        Route::get('/admin/services', [AdminController::class, 'services'])->name('admin.services');
        Route::post('/admin/services', [AdminController::class, 'storeService'])->name('admin.services.store');
        Route::patch('/admin/services/{service}', [AdminController::class, 'updateService'])->name('admin.services.update');
        Route::delete('/admin/services/{service}', [AdminController::class, 'destroyService'])->name('admin.services.destroy');

        Route::get('/admin/employees', [AdminController::class, 'employees'])->name('admin.employees');
        Route::post('/admin/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
        Route::delete('/admin/employees/{user}', [AdminController::class, 'destroyEmployee'])->name('admin.employees.destroy');

        Route::get('/admin/vouchers', [AdminController::class, 'vouchers'])->name('admin.vouchers');
        Route::post('/admin/vouchers', [AdminController::class, 'storeVoucher'])->name('admin.vouchers.store');
        Route::delete('/admin/vouchers/{voucher}', [AdminController::class, 'destroyVoucher'])->name('admin.vouchers.destroy');
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// web.php
Route::get('/payment/pay/{order}', [PaymentController::class, 'createInvoice'])->name('payment.pay');

require __DIR__.'/auth.php';
