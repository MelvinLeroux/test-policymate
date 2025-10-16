<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        //stressed at the beggining and didn't harmonize with the migrations
        'order_id',
        'order_date',
        'customer_email',
    ];

        public function orderInfo()
    {
        return $this->hasMany(OrderInfo::class);
    }
}
