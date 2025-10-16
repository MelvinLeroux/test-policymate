<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;

class TopProductController extends Controller
{
    public function topProductsByRevenue($top = 10)
    {
    $products = Product::with('orderInfo')->get();
    $products = $products->map(function ($product) {
        $product->revenue = $product->orderInfo->sum(function ($orderLine) {
            return $orderLine->quantity * $orderLine->price;
            });
            return $product;
        });

        $topProducts = $products->sortByDesc('revenue')->take($top);

        $formatted = $topProducts->map(function ($product) {
            return "{$product->sku} / {$product->name} €" . number_format($product->revenue, 2);
        });

        return response()->json($formatted);
    }
}
