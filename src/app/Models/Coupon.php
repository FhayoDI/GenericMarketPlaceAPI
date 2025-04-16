<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    public $fillable = [
        "code",
        "type",
        "value",
        "expiresAt",
        "usageLimit",
        "used",
    ];
    protected $datas = ["expiresAt"];

    public function isValid(){
        return $this->expiresAt->isFuture() && 
            ($this->usageLimit == null || $this->used < $this->usageLimit);   
    }
    public function order() 
    {
        return $this->hasMany(Order::class);
    }
}
