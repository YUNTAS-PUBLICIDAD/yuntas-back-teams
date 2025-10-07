<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoEtiqueta extends Model
{
    use HasFactory;

    protected $table = 'producto_etiquetas';

    protected $fillable = [
        'producto_id',
        'meta_titulo',
        'meta_descripcion',
         'keywords',
    ];

    protected $casts = [
        'keywords' => 'array',
    ];
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
