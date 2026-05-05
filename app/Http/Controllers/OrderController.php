<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
        ]);

        $sharedOrderNumber = Order::generateOrderNumber();
        $createdOrders = [];
        $totalGrandPrice = 0;
        
        // Loop through each item and create an order
        foreach ($request->items as $item) {
            $service = Service::where('name', $item['service_name'])->first();

            $totalPrice = (int) $item['total_price'];
            $additionalFees = (int) ($item['additional_fees'] ?? 0);
            $totalPrice += $additionalFees;

            // Apply voucher logic per item or globally?
            // Usually, a voucher applies to the whole cart, but since orders are individual records, 
            // we will apply it proportionally or just let the first item absorb it?
            // To keep it simple, we will apply the discount amount calculated from the total of all items to the first item, 
            // or split it. Actually, it's easier to just calculate it per item if it's a percentage.
            // If it's a fixed amount, it's tricky. Let's just calculate it per item for now.
            $discountAmount = 0;
            $voucherCode = null;

            if ($request->voucher_code) {
                $voucher = \App\Models\Voucher::where('code', $request->voucher_code)->where('is_active', true)->first();
                if ($voucher) {
                    $voucherCode = $voucher->code;
                    if ($voucher->discount_type == 'percent') {
                        $discountAmount = $totalPrice * ($voucher->discount_amount / 100);
                    } else {
                        // Fixed amount spread across items
                        $discountAmount = $voucher->discount_amount / count($request->items);
                    }
                    
                    if ($discountAmount > $totalPrice) {
                        $discountAmount = $totalPrice;
                    }
                    $totalPrice -= $discountAmount;
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
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'status' => 'Waiting', // Default status
                'notes' => $request->notes,
                'voucher_code' => $voucherCode,
                'discount_amount' => $discountAmount,
                'created_by' => auth()->user()->name,
            ]);

            $createdOrders[] = $order;
            $totalGrandPrice += $totalPrice;
        }

        // For payment integration, usually it expects a single order. If multiple, we might need a grouping logic.
        // For now, if not cash, redirect to payment for the FIRST order (this might be a limitation of flat schema).
        // To be safe, we redirect to orders list if there are multiple. If single, go to payment.
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => count($createdOrders) . ' pesanan berhasil dibuat!',
            ]);
        }

        if (count($createdOrders) === 1 && $request->payment_method !== 'Cash') {
            return redirect()->route('payment.pay', $createdOrders[0]);
        }

        return redirect()->route('admin.orders')->with('success', count($createdOrders) . ' pesanan berhasil dibuat untuk ' . $request->customer_name);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Pesanan berhasil dihapus.');
    }
}
