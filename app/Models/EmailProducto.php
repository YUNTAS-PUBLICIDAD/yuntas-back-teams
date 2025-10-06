<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailProducto extends Model
{
    protected $fillable = [
        'producto_id',
        'titulo',
        'parrafo1',
        'imagen_principal',
        'imagenes_secundarias',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
