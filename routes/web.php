<?php

use App\Http\Controllers\ProfileController;
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
        if (auth()->user()->role === 'owner') {
            return redirect()->route('admin.dashboard');
        }
        // Karyawan dan semua role lainnya ke halaman pesanan
        return redirect()->route('admin.orders');
    })->name('dashboard');

    Route::middleware('is_admin')->group(function () {
        // Real admin dashboard for graph & export
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/orders/export', [AdminController::class, 'exportExcel'])->name('admin.orders.export');

        // Karyawan & Owner - Order Management
        Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::get('/admin/orders/create', function () {
            $services = \App\Models\Service::all();
            $add_ons = \App\Models\AddOn::all();
            return view('admin.orders-create', compact('services', 'add_ons'));
        })->name('admin.orders.create');
        Route::post('/admin/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
        Route::get('/admin/orders/{order}/edit', [AdminController::class, 'editOrder'])->name('admin.orders.edit');
        Route::put('/admin/orders/{order}', [AdminController::class, 'updateOrder'])->name('admin.orders.update');
        Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

        // Owner Only
        Route::get('/admin/services', [AdminController::class, 'services'])->name('admin.services');
        Route::post('/admin/services', [AdminController::class, 'storeService'])->name('admin.services.store');
        Route::patch('/admin/services/{service}', [AdminController::class, 'updateService'])->name('admin.services.update');
        Route::delete('/admin/services/{service}', [AdminController::class, 'destroyService'])->name('admin.services.destroy');

        Route::post('/admin/add-ons', [AdminController::class, 'storeAddOn'])->name('admin.add_ons.store');
        Route::patch('/admin/add-ons/{add_on}', [AdminController::class, 'updateAddOn'])->name('admin.add_ons.update');
        Route::delete('/admin/add-ons/{add_on}', [AdminController::class, 'destroyAddOn'])->name('admin.add_ons.destroy');

        Route::get('/admin/employees', [AdminController::class, 'employees'])->name('admin.employees');
        Route::post('/admin/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
        Route::patch('/admin/employees/{user}', [AdminController::class, 'updateEmployee'])->name('admin.employees.update');
        Route::delete('/admin/employees/{user}', [AdminController::class, 'destroyEmployee'])->name('admin.employees.destroy');

        Route::get('/admin/vouchers', [AdminController::class, 'vouchers'])->name('admin.vouchers');
        Route::post('/admin/vouchers', [AdminController::class, 'storeVoucher'])->name('admin.vouchers.store');
        Route::patch('/admin/vouchers/{voucher}', [AdminController::class, 'updateVoucher'])->name('admin.vouchers.update');
        Route::delete('/admin/vouchers/{voucher}', [AdminController::class, 'destroyVoucher'])->name('admin.vouchers.destroy');
        Route::post('/admin/vouchers/check', [\App\Http\Controllers\VoucherController::class, 'check'])->name('admin.vouchers.check');
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Payment
Route::get('/payment/pay/{order}', [PaymentController::class, 'createInvoice'])->name('payment.pay');

require __DIR__.'/auth.php';
