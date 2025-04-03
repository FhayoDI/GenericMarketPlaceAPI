<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public $fillable = [
         "created_at",
         "user_id"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function cartItem()  
    {
        return $this->hasMany(CartItem::class);
    }
}
