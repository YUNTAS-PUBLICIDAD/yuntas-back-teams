<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory;

    protected $table = 'blogs';
    protected $primaryKey = 'id_blog'; // Especificar la clave primaria correcta

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen_principal',
        'estatus',
        'fecha_creacion',
        'fecha_actualizacion'
    ];

    public $timestamps = false;
    
    /**
     * Obtener los bloques de contenido asociados con el blog.
     */
    public function bloquesContenido(): HasMany
    {
        return $this->hasMany(BloqueContenido::class, 'id_blog', 'id_blog')->orderBy('orden');
    }
}
