<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especificacion extends Model
{
    protected $table = 'especificaciones';
    
    protected $fillable = [
        'producto_id',
        'clave',
        'valor'
    ];

    public $timestamps = true;

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
