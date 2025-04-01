<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAdress extends Model
{
    use HasFactory;
    protected $fillable = [ 
        "street",
        "number",
        "cep",
        "city",
        "state",
        "country",
    ];
    protected function userAdress(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }  
    protected function adressHistoric():HasMany
    {
        return $this->hasMany(Historic::class);
    }

}
