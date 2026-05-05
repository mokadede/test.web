<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function orders(Request $request)
    {
        $query = Order::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('created_by', 'LIKE', "%{$search}%");
            });
        }

        // Date Filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Allowed columns to avoid SQL injection
        $allowedSorts = ['order_number', 'customer_name', 'estimated_days', 'total_price', 'status', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $orders = $query->get();

        if ($request->ajax()) {
            return view('admin.partials.orders-table', compact('orders'));
        }

        return view('admin.orders', compact('orders'));
    }

    public function services()
    {
        $this->checkOwner();
        $services = Service::all();
        $add_ons = \App\Models\AddOn::all();
        return view('admin.services', compact('services', 'add_ons'));
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
        $services = Service::all();
        return view('admin.orders-edit', compact('order', 'services'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'shoe_brand' => 'nullable|string|max:255',
            'shoe_size' => 'nullable|string|max:50',
            'shoe_condition' => 'nullable|string|max:100',
            'service_name' => 'required|string|max:255',
            'additional_fees' => 'nullable|integer|min:0',
            'total_price' => 'required|integer|min:0',
            'payment_method' => 'nullable|string',
            'payment_status' => 'required|in:unpaid,paid',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Auto-set category and estimated_days from service
        $service = Service::where('name', $request->service_name)->first();

        $order->update([
            'customer_name' => $request->customer_name,
            'phone_number' => $request->phone_number,
            'shoe_brand' => $request->shoe_brand,
            'shoe_size' => $request->shoe_size,
            'shoe_condition' => $request->shoe_condition,
            'service_category' => $service ? $service->category : $request->service_category,
            'service_name' => $request->service_name,
            'additional_fees' => $request->additional_fees ?? 0,
            'total_price' => $request->total_price,
            'estimated_days' => $service ? $service->estimated_days : $order->estimated_days,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.orders')->with('success', 'Data pesanan berhasil diperbarui.');
    }

    // Owner Only Methods
    private function checkOwner() {
        abort_if(auth()->user()->role !== 'owner', 403, 'Akses ditolak. Hanya untuk Owner.');
    }

    public function dashboard(\Illuminate\Http\Request $request)
    {
        $this->checkOwner();
        
        $currentYear = date('Y');
        $query = Order::where('payment_status', 'paid');

        // Apply date filters if provided, otherwise default to current year
        if ($request->filled('start_date') || $request->filled('end_date')) {
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
        } else {
            $query->whereYear('created_at', $currentYear);
        }

        $orders = $query->get();
            
        $chartData = array_fill(1, 12, 0);
        $totalRevenue = 0;
        $totalPaidOrders = $orders->count();
        $serviceCounts = [];

        foreach ($orders as $order) {
            $month = (int) $order->created_at->format('m');
            $chartData[$month] += $order->total_price;
            $totalRevenue += $order->total_price;

            if ($order->service_name) {
                if (!isset($serviceCounts[$order->service_name])) {
                    $serviceCounts[$order->service_name] = 0;
                }
                $serviceCounts[$order->service_name]++;
            }
        }

        arsort($serviceCounts);
        $topService = key($serviceCounts);

        $summary = [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalPaidOrders,
            'top_service' => $topService,
            'year' => $currentYear
        ];

        // Sort orders descending by created_at for the table
        $recentOrders = $orders->sortByDesc('created_at')->take(10); // Show latest 10 or all? Let's just pass all $orders and let view handle it or just recent 10. Let's pass $recentOrders.
        // Actually, "rincian order customer" usually means the list of orders that made up the chart. Let's pass all orders, or paginate.
        // Since it's a dashboard, maybe just recent ones, or all orders in the current year. Let's pass the $orders collection we already queried.
        // We'll pass it as $orders.

        if (request()->ajax()) {
            return response()->json([
                'chartData' => array_values($chartData),
                'summary' => $summary,
                'summary_formatted' => [
                    'revenue' => 'Rp ' . number_format($summary['total_revenue'], 0, ',', '.'),
                    'orders' => $summary['total_orders'] . ' Pesanan',
                    'top_service' => $summary['top_service'] ?: 'Belum Ada',
                    'year_text' => request('start_date') || request('end_date') ? '(Filter Aktif)' : 'Tahun ' . $summary['year']
                ],
                'html' => view('admin.partials.dashboard-orders', compact('orders'))->render()
            ]);
        }

        return view('admin.dashboard', compact('chartData', 'summary', 'orders'));
    }

    public function exportExcel(Request $request)
    {
        $this->checkOwner();

        $period = $request->get('period');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Generate a descriptive filename
        $label = match($period) {
            '7days' => '7_hari_terakhir',
            '30days' => '30_hari_terakhir',
            'this_month' => 'bulan_ini',
            'last_month' => 'bulan_lalu',
            default => ($startDate && $endDate) ? "{$startDate}_sd_{$endDate}" : 'semua_data',
        };

        $filename = "K-Clean_Laporan_{$label}_" . date('Ymd_His') . ".xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\OrdersExport($startDate, $endDate, $period),
            $filename
        );
    }

    public function storeService(Request $request)
    {
        $this->checkOwner();
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'estimated_days' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        Service::create($request->only(['category', 'name', 'price', 'estimated_days', 'description']));
        return back()->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function updateService(Request $request, Service $service)
    {
        $this->checkOwner();
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'estimated_days' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);
        $service->update($request->only(['category', 'name', 'price', 'estimated_days', 'description']));
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

    public function updateEmployee(Request $request, \App\Models\User $user)
    {
        $this->checkOwner();
        if ($user->role !== 'karyawan') {
            return back()->with('error', 'Hanya dapat mengedit karyawan.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Karyawan berhasil diperbarui.');
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

    public function updateVoucher(Request $request, \App\Models\Voucher $voucher)
    {
        $this->checkOwner();
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'discount_amount' => 'required|numeric',
            'discount_type' => 'required|in:fixed,percent',
        ]);
        $voucher->update($request->all());
        return back()->with('success', 'Voucher berhasil diperbarui.');
    }

    public function storeAddOn(Request $request)
    {
        $this->checkOwner();
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        \App\Models\AddOn::create($request->only(['name', 'price']));
        return back()->with('success', 'Add-on berhasil ditambahkan.');
    }

    public function updateAddOn(Request $request, \App\Models\AddOn $add_on)
    {
        $this->checkOwner();
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        $add_on->update($request->only(['name', 'price']));
        return back()->with('success', 'Add-on berhasil diperbarui.');
    }

    public function destroyAddOn(\App\Models\AddOn $add_on)
    {
        $this->checkOwner();
        $add_on->delete();
        return back()->with('success', 'Add-on berhasil dihapus.');
    }
}
