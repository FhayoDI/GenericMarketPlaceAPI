<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Products extends Model
{
    use HasFactory;
    public $fillable = [
        "category_name",
        "category_id",
        "name",
        "stock",
        "price",
        "description",
    ];
    public function category(): BelongsTo{
        return $this->belongsTo(Products::class);
    }
}
