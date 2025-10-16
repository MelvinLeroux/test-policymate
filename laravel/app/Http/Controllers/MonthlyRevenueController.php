<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;


class MonthlyRevenueController extends Controller
{
    public function monthlyRevenue($year)
    {
        $orders = Order::with('orderInfo')
            ->whereYear('created_at', $year)
            ->get();

        if ($orders->isEmpty()) {
            return response()->json("No result for this year : {$year}");
        }

        $monthlyRevenue = $orders
            ->flatMap(fn($order) => $order->orderInfo)
            ->groupBy(fn($orderLine) => $orderLine->order->created_at->format('Y-m'))
            ->map(fn($lines, $month) => "{$month} €" . number_format($lines->sum(fn($line) => $line->quantity * $line->price), 2));

        return response()->json($monthlyRevenue->values());
    }
}
