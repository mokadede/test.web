<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Xendit\Xendit;

class PaymentController extends Controller
{
    public function __construct()
    {
        Xendit::setApiKey(config('xendit.secret_key'));
    }

    public function createInvoice(Order $order)
    {
        $params = [
            'external_id' => 'ORDER-' . $order->order_number . '-' . time(),
            'amount' => (int) $order->total_price,
            'description' => 'Pembayaran Shoe Laundry #' . $order->order_id_formatted,
            'payer_email' => 'customer@shoelaundry.com',
            'success_redirect_url' => route('admin.orders'),
            'failure_redirect_url' => route('admin.orders'),
            'currency' => 'IDR',
        ];

        try {
            $invoice = \Xendit\Invoice::create($params);
            
            $order->update([
                'external_id' => $invoice['id'],
            ]);

            return redirect($invoice['invoice_url']);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        // 1. Verify token
        if ($request->header('x-callback-token') !== config('xendit.webhook_token')) {
            return response()->json(['message' => 'Invalid token'], 403);
        }

        $external_id = $request->external_id;
        $status = $request->status;

        // Extract order number: ORDER-ABC12-1714545678 -> ABC12
        $parts = explode('-', $external_id);
        $order_number = $parts[1] ?? null;

        $order = Order::where('order_number', $order_number)->first();

        if ($order) {
            if ($status === 'PAID' || $status === 'SETTLED') {
                $order->update(['payment_status' => 'paid']);
            } elseif ($status === 'EXPIRED') {
                $order->update(['payment_status' => 'expired']);
            }
        }

        return response()->json(['message' => 'Webhook received']);
    }
}
