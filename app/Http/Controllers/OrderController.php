<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%")
                  ->orWhere('phone_number', 'like', "%{$request->search}%")
                  ->orWhere('created_by', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'payment_method' => 'required|string|in:QRIS,Transfer Bank,Cash',
            'notes' => 'nullable|string',
            'voucher_code' => 'nullable|string|exists:vouchers,code',
            'items' => 'required|array|min:1',
            'items.*.shoe_brand' => 'nullable|string|max:255',
            'items.*.shoe_size' => 'nullable|string|max:50',
            'items.*.shoe_condition' => 'nullable|string|max:100',
            'items.*.service_name' => 'required|string|max:255',
            'items.*.additional_fees' => 'nullable|integer|min:0',
            'items.*.total_price' => 'required|integer|min:0',
            'items.*.estimated_days' => 'nullable|string|max:100',
            'items.*.add_ons' => 'nullable|array',
            'items.*.add_ons.*.name' => 'nullable|string|max:255',
            'items.*.add_ons.*.price' => 'nullable|integer|min:0',
        ]);

        $sharedOrderNumber = Order::generateOrderNumber();
        $createdOrders = [];
        $totalGrandPrice = 0;
        
        // Loop melalui setiap item dan buat pesanan
        foreach ($request->items as $item) {
            $service = Service::where('name', $item['service_name'])->first();
            
            $totalPrice = (int) ($item['total_price'] ?? 0);
            $additionalFees = (int) ($item['additional_fees'] ?? 0);
            $voucherCode = $request->voucher_code;
            
            // Jika diskon dikirim dari frontend, gunakan itu. 
            // Jika tidak (misal dari web admin lama), baru kalkulasi manual.
            $discountAmount = 0;
            if ($request->has('discount_amount')) {
                // Bagi diskon ke setiap item jika dikirim totalnya
                $discountAmount = (float) $request->discount_amount / count($request->items);
            } else if ($request->voucher_code) {
                $voucher = \App\Models\Voucher::where('code', $request->voucher_code)->where('is_active', true)->first();
                if ($voucher) {
                    if ($voucher->discount_type == 'percent') {
                        $discountAmount = $totalPrice * ($voucher->discount_amount / 100);
                    } else {
                        $discountAmount = $voucher->discount_amount / count($request->items);
                    }
                }
            }

            $order = Order::create([
                'order_number' => $sharedOrderNumber,
                'customer_name' => $request->customer_name,
                'phone_number' => $request->phone_number,
                'shoe_brand' => $item['shoe_brand'] ?? null,
                'shoe_size' => $item['shoe_size'] ?? null,
                'shoe_condition' => $item['shoe_condition'] ?? null,
                'service_category' => $service ? $service->category : ($item['service_category'] ?? null),
                'service_name' => $item['service_name'],
                'additional_fees' => $additionalFees,
                'total_price' => $totalPrice,
                'estimated_days' => $item['estimated_days'] ?? ($service ? $service->estimated_days : null),
                'add_ons' => isset($item['add_ons']) ? json_encode($item['add_ons']) : null,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status ?? 'unpaid',
                'status' => 'Waiting', // Default status
                'notes' => $request->notes,
                'voucher_code' => $voucherCode,
                'discount_amount' => $discountAmount,
                'created_by' => auth()->user()->name,
            ]);

            $createdOrders[] = $order;
            $totalGrandPrice += $totalPrice;
        }

        // Increment used_count jika menggunakan voucher
        if ($request->voucher_code) {
            $voucher = \App\Models\Voucher::where('code', $request->voucher_code)->first();
            if ($voucher) {
                $voucher->increment('used_count');
            }
        }

        // For payment integration, usually it expects a single order. If multiple, we might need a grouping logic.
        // For now, if not cash, redirect to payment for the FIRST order (this might be a limitation of flat schema).
        // To be safe, we redirect to orders list if there are multiple. If single, go to payment.
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'order_number' => $sharedOrderNumber,
                'message' => count($createdOrders) . ' pesanan berhasil dibuat!',
            ]);
        }

        if (count($createdOrders) === 1 && $request->payment_method !== 'Cash') {
            return redirect()->route('payment.pay', $createdOrders[0]);
        }

        return redirect()->route('admin.orders')->with('success', count($createdOrders) . ' pesanan berhasil dibuat untuk ' . $request->customer_name);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }
        
        $order->update($request->only([
            'status', 'payment_status', 'notes', 'customer_name', 'phone_number',
            'shoe_brand', 'shoe_size', 'shoe_condition', 'service_name',
            'additional_fees', 'add_ons', 'total_price', 'estimated_days', 'payment_method',
            'voucher_code', 'discount_amount'
        ]));
        
        // If add_ons is passed as array, encode it to JSON
        if ($request->has('add_ons') && is_array($request->add_ons)) {
            $order->update(['add_ons' => json_encode($request->add_ons)]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diperbarui',
            'order' => $order
        ]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }
        
        $order->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus',
            ]);
        }

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }
}
