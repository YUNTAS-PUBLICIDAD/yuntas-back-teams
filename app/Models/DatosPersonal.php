<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DatosPersonal extends Model
{
    use HasFactory;

    protected $fillable = [
        'datos',
        'tipo_doc',
        'numero_doc',
        'correo',
        'telefono'
    ];

    public $timestamps = true;

    public function reclamos(): HasMany
    {
        return $this->hasMany(Reclamo::class, 'id_data','id');
    }

}
