<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    use HasFactory;
    
    public $fillable = [
        "id",
        "description",
        "startDate",
        "endDate",
    ];
    public function item(){
        return $this->belongsTo(Products::class);
    }
}
