<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    use HasFactory;
    
    public $fillable = [
        "productId",
        "discount_percentage",
        "description",
        "start_date",
        "end_date",
    ];
    public function item(){
        return $this->belongsTo(Products::class);
    }
}
