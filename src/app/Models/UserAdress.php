<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAdress extends Model
{
    use HasFactory;
    public $fillable = [ 
        "user_id",
        "street",
        "number",
        "cep",
        "city",
        "state",
        "country",
    ];
    public function userAdress(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }  
    public function order():HasMany
    {
        return $this->hasMany(Order::class);
    }

}
