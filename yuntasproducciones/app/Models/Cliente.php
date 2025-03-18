<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'celular',
    ];

    public $timestamps = true;

    public function interesados(): HasMany
    {
        return $this->hasMany(Interesado::class, 'cliente_id', 'id');
    }
}