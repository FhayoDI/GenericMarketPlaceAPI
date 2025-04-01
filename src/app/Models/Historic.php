<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Historic extends Model
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
    protected function userHistoric():BelongsTo
    {
        return $this->belongsTo(UserAdress::class);
    }
}
