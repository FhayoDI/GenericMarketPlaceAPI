<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Products extends Model
{
    use HasFactory;
    public $fillable = [
        "category_id",
        "name",
        "stock",
        "price",
        "description",
    ];  
    public function category(): BelongsTo{
        return $this->belongsTo(Category::class);
    }
    public function order(){
        return $this->hasMany(Order::class);
    }
    public function discount() 
    {
        return $this->hasMany(Discounts::class); 
    }
    public function cartItem()
    {
        return $this->hasMany(CartItem::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
