<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    public $fillable = [
    "name",
    "cart_id",
    "product_id",
    "quantity",
    "unit_price",
    ];
    
}
