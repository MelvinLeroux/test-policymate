<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'id',
        'date',
        'customer_email',
    ];

        public function orderLines()
    {
        return $this->hasMany(OrderInfo::class);
    }
}
