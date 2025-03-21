<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reclamo extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_compra',
        'producto',
        'detalle_reclamo',
        'monto_reclamo',
        'id_data'
    ];

    public $timestamps = true;

    public function personales(): BelongsTo
    {
        return $this->belongsTo(DatosPersonal::class,'id_data');
    }

}
