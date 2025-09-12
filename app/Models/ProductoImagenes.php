<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoImagenes extends Model
{
    //
    protected $table = "producto_imagenes";
    protected $fillable = [
        'url_imagen',
        'texto_alt_SEO',
        'tipo'
    ];
    public $timestamps = true;
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
