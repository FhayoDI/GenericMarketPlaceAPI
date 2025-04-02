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
        "adress_id",
        "orderDate",
        "coupon_id",
        "status",
        "total_amount",
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
        return $this->belongsTo(Order::class);
    }
}
