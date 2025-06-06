<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model   
{
    use HasFactory;
    
    public $fillable = [
        "order_id",
        "product_id",
        "quantity",
        "unit_price",
        "applied_discount",
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
