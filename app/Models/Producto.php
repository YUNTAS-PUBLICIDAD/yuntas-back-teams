<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductoImagenes;
use App\Http\Controllers\Api\V1\Blog\BlogController;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'link',
        'titulo',
        'seccion',
        'descripcion',
        'imagen_principal',
        'especificaciones',
        'beneficios'
    ];
    // Campos que deben ser tratados como JSON
    protected $casts = [
        'especificaciones' => 'array',
        'beneficios' => 'array',
    ];

    public $timestamps = true;

    public function imagenes()
    {
        return $this->hasMany(ProductoImagenes::class, 'producto_id');
    }

    public function interesados(): HasMany
    {
        return $this->hasMany(Interesado::class, 'producto_id', 'id');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'producto_id', 'id');
    }

    public function especificaciones(){
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
}
