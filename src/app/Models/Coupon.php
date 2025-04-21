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

    public function isValid()
{
    $now = now();
    $isExpired = $this->expiresAt <= $now;
    $overLimit = $this->usageLimit && $this->used >= $this->usageLimit;
    return !$isExpired && !$overLimit;
}
    public function order() 
    {
        return $this->hasMany(Order::class);
    }

}
