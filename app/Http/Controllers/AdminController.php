<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function orders()
    {
        $orders = Order::with('user', 'items.service')->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    public function services()
    {
        $this->checkOwner();
        $services = Service::all();
        return view('admin.services', compact('services'));
    }

    public function employees()
    {
        $this->checkOwner();
        $employees = \App\Models\User::where('role', 'karyawan')->get();
        return view('admin.employees', compact('employees'));
    }

    public function vouchers()
    {
        $this->checkOwner();
        $vouchers = \App\Models\Voucher::all();
        return view('admin.vouchers', compact('vouchers'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // Owner Only Methods
    private function checkOwner() {
        abort_if(auth()->user()->role !== 'owner', 403, 'Akses ditolak. Hanya untuk Owner.');
    }

    public function storeService(Request $request)
    {
        $this->checkOwner();
        $request->validate(['name' => 'required', 'price' => 'required|numeric']);
        Service::create($request->all());
        return back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function updateService(Request $request, Service $service)
    {
        $this->checkOwner();
        $request->validate(['name' => 'required', 'price' => 'required|numeric']);
        $service->update($request->all());
        return back()->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroyService(Service $service)
    {
        $this->checkOwner();
        $service->delete();
        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    public function storeEmployee(Request $request)
    {
        $this->checkOwner();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        
        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'karyawan',
        ]);
        return back()->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function destroyEmployee(\App\Models\User $user)
    {
        $this->checkOwner();
        if ($user->role === 'karyawan') {
            $user->delete();
        }
        return back()->with('success', 'Karyawan berhasil dihapus.');
    }

    public function storeVoucher(Request $request)
    {
        $this->checkOwner();
        $request->validate([
            'code' => 'required|unique:vouchers',
            'discount_amount' => 'required|numeric',
            'discount_type' => 'required|in:fixed,percent',
        ]);
        \App\Models\Voucher::create($request->all());
        return back()->with('success', 'Voucher berhasil dibuat.');
    }

    public function destroyVoucher(\App\Models\Voucher $voucher)
    {
        $this->checkOwner();
        $voucher->delete();
        return back()->with('success', 'Voucher berhasil dihapus.');
    }
}
