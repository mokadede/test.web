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
            'tracking_code' => 'required|string', // This will now be our Order ID (KC-XXXXX)
            'phone_last_4' => 'required|string|size:4',
        ]);

        $query = strtoupper($request->tracking_code);
        
        // Remove 'KC-' prefix if user entered it
        $cleanId = str_replace('KC-', '', $query);

        $orders = Order::where('order_number', $cleanId)->get();

        // Verify the last 4 digits of the phone number using the first order found
        if ($orders->isNotEmpty() && substr($orders->first()->phone_number, -4) !== $request->phone_last_4) {
            $orders = collect();
        }

        return view('tracking', [
            'orders' => $orders,
            'searched' => true,
            'query' => $query,
            'phone_query' => $request->phone_last_4,
        ]);
    }
}
