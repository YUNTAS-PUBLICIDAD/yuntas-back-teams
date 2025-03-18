<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    protected $fillable = [
        'id_producto',
        'tipo',
        'valor'
    ];

    public $timestamps = true;

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
