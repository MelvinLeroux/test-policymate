<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;

class TopCustomerController extends Controller
{
    public function topCustomersByRevenue($top = 10)
    {
        $orders = Order::with('orderInfo')->get();
        if ($orders->isEmpty()) {
            return response()->json("No orders for now");
        }

        $customers = $orders->groupBy('customer_email')->map(function ($orders, $email) {
            $revenue = $orders->flatMap(fn($order) => $order->orderInfo)
                ->sum(fn($line) => $line->quantity * $line->price);
            $orderCount = $orders->count();
            return "{$email} €" . number_format($revenue, 2) . " {$orderCount} " . ($orderCount > 1 ? "orders" : "order");
        });
        //used IA there, because I didn't know how to manage with the "collection of collection problem"
        $topCustomers = $customers->sortByDesc(fn($line) => (float) filter_var($line, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION))
            ->take($top);

        return response()->json($topCustomers->values());
    }
}
