<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    //links an order to a product, it contains the quantity and the unit price of every products inside an order
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price'
    ];

   public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
