<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoDetalle extends Model
{
    use HasFactory;

    protected $table = 'producto_detalle';
    protected $primaryKey = 'id_detallas_produc';
    public $timestamps = false;
    protected $fillable = [
        'id_produc',
        'especificacion',
        'informacion',
        'beneficios_01',
        'beneficios_02',
        'beneficios_03',
        'beneficios_04',
        'img_card',
        'img_portada_01',
        'img_portada_02',
        'img_portada_03',
        'img_esp',
        'img_benef',
    ];
}
