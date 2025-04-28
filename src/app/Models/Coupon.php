<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "code",
        "type",
        "value",
        "expires_at",  
        "usage_limit", 
    ];
    
    protected $dates = ["expires_at"]; 

    public function isValid()
    {
        $notExpired = true;
        if ($this->expires_at !== null) {
            if (!$this->expires_at instanceof Carbon) {
                $this->expires_at = Carbon::parse($this->expires_at);
            }
            $notExpired = $this->expires_at->isFuture();
        }

        $withinUsageLimit = ($this->usage_limit === null) || ($this->used < $this->usage_limit);

        return $notExpired && $withinUsageLimit;
    }

    public function orders() 
    {
        return $this->hasMany(Order::class);
    }
}