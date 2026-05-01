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

    public function editOrder(Order $order)
    {
        $order->load('items.service');
        $services = Service::all();
        return view('admin.orders-edit', compact('order', 'services'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid',
            'status' => 'required|string',
            'items' => 'required|array',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
            'status' => $request->status,
        ]);

        foreach ($request->items as $id => $itemData) {
            $item = \App\Models\OrderItem::findOrFail($id);
            if ($item->order_id == $order->id) {
                $item->update([
                    'service_id' => $itemData['service_id'],
                    'shoe_name' => $itemData['shoe_name'],
                    'shoe_brand' => $itemData['shoe_brand'] ?? null,
                    'shoe_size' => $itemData['shoe_size'] ?? null,
                    'shoe_material' => $itemData['shoe_material'] ?? null,
                ]);
            }
        }

        $totalPrice = 0;
        foreach ($order->items()->get() as $item) {
            $totalPrice += $item->service->price;
        }
        
        $discountAmount = 0;
        if ($order->voucher_code) {
            $voucher = \App\Models\Voucher::where('code', $order->voucher_code)->first();
            if ($voucher) {
                if ($voucher->discount_type == 'percent') {
                    $discountAmount = $totalPrice * ($voucher->discount_amount / 100);
                } else {
                    $discountAmount = $voucher->discount_amount;
                }
                if ($discountAmount > $totalPrice) $discountAmount = $totalPrice;
            }
        }

        $order->update([
            'total_price' => $totalPrice - $discountAmount,
            'discount_amount' => $discountAmount
        ]);

        return redirect()->route('admin.orders')->with('success', 'Data pesanan berhasil diperbarui.');
    }

    // Owner Only Methods
    private function checkOwner() {
        abort_if(auth()->user()->role !== 'owner', 403, 'Akses ditolak. Hanya untuk Owner.');
    }

    public function dashboard()
    {
        $this->checkOwner();
        
        $orders = Order::where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->get();
            
        $chartData = array_fill(1, 12, 0);
        foreach ($orders as $order) {
            $month = (int) $order->created_at->format('m');
            $chartData[$month] += $order->total_price;
        }

        return view('admin.dashboard', compact('chartData'));
    }

    public function exportExcel()
    {
        $this->checkOwner();
        $orders = Order::with('user', 'items.service')->where('payment_status', 'paid')->get();
        
        $filename = "orders_export_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, ['Tracking Code', 'Pelanggan', 'Layanan', 'Total Harga', 'Tanggal', 'Metode Pembayaran']);

        foreach($orders as $order) {
            $services = $order->items->map(function($item) {
                return $item->shoe_name . ' (' . $item->service->name . ')';
            })->implode(', ');
            
            fputcsv($handle, [
                $order->tracking_code,
                $order->user->name,
                $services,
                $order->total_price,
                $order->created_at->format('Y-m-d'),
                $order->payment_method
            ]);
        }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
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
