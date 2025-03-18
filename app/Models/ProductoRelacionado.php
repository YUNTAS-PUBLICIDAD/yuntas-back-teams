<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoRelacionado extends Model
{
    protected $table = 'producto_relacionados';
    
    protected $fillable = [
        'id_producto',
        'id_relacionado'
    ];

    public $timestamps = true;

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function relacionado()
    {
        return $this->belongsTo(Producto::class, 'id_relacionado');
    }
}
