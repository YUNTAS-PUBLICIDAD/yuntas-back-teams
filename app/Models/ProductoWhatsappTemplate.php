<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoWhatsappTemplate extends Model
{
    //
    protected $table = 'producto_whatsapp_templates';

    protected $fillable = [
        'producto_id','nombre','imagen_principal','titulo','parrafo'
    ];

    public function producto(){
        return $this->belongsTo(Producto::class);
    }
}
