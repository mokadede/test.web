<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month; // Optional

        $query = Order::whereYear('created_at', $year);
        
        if ($month) {
            $query->whereMonth('created_at', $month);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Calculate stats
        $paidOrders = $orders->where('payment_status', 'paid');
        $totalRevenue = $paidOrders->sum('total');
        $totalVolume = $paidOrders->count();
        $totalAllOrders = $orders->count();

        // Monthly revenue breakdown for charts
        $monthlyRevenue = array_fill(1, 12, 0);
        foreach ($paidOrders as $order) {
            $m = (int) $order->created_at->format('m');
            $monthlyRevenue[$m] += $order->total;
        }

        // Service ranking
        $serviceRanking = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $serviceName = $item->service_name;
                if ($serviceName) {
                    if (!isset($serviceRanking[$serviceName])) {
                        $serviceRanking[$serviceName] = 0;
                    }
                    $serviceRanking[$serviceName]++;
                }
            }
        }
        
        arsort($serviceRanking);
        $formattedRanking = [];
        foreach ($serviceRanking as $name => $count) {
            $formattedRanking[] = ['name' => $name, 'count' => $count];
        }

        return response()->json([
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_volume' => $totalVolume,
                'total_orders' => $totalAllOrders,
            ],
            'chart_data' => array_values($monthlyRevenue),
            'service_ranking' => array_slice($formattedRanking, 0, 5),
            'paid_orders' => $paidOrders->values(), // All paid orders for the table
        ]);
    }
}
