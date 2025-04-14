<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    public $fillable = [
        "code",
        "start_date",
        "end_date",
        "discount_percentage",
    ];

    public function order() 
    {
        return $this->hasMany(Order::class);
    }
}
