<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Show the tracking search page.
     */
    public function index()
    {
        return view('tracking');
    }

    /**
     * Search for an order by tracking code.
     */
    public function search(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string|max:5',
            'whatsapp_last_4' => 'required|string|size:4',
        ]);

        $order = Order::with(['items.service', 'user'])
            ->where('tracking_code', strtoupper($request->tracking_code))
            ->first();

        // Verify the last 4 digits of the WhatsApp number
        if ($order && substr($order->user->whatsapp, -4) !== $request->whatsapp_last_4) {
            $order = null;
        }

        return view('tracking', [
            'order' => $order,
            'searched' => true,
            'query' => strtoupper($request->tracking_code),
            'whatsapp_query' => $request->whatsapp_last_4,
        ]);
    }
}
