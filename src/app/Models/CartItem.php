<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    public function cart() : BelongsTo {
        return $this->belongsTo(Cart::class);
    }
    public function product() : HasOne {
        return $this->hasOne(Products::class);
    }
}
