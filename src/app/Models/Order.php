<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;

class Order extends Model
{
    use HasFactory;
    public $fillable = [
        "user_id",
        "address_id",
        "order_date",
        "coupon_id",
        "status",
        "subtotal",
        "products_discount",
        "coupon_discount",
        "total_amount"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function adress(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class,'coupon_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
