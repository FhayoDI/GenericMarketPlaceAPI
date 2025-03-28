<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function userAdress(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }
}
