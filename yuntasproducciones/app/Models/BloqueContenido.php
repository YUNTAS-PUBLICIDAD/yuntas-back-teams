<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueContenido extends Model
{
    /** @use HasFactory<\Database\Factories\BloqueContenidoFactory> */
    use HasFactory;

    protected $table = 'bloque_contenidos';

    protected $fillable = [
        'id_blog',
        'parrafo',
        'imagen',
        'descripcion_imagen',
        'orden',
        'fecha_creacion',
        'fecha_actualizacion'
    ];

    public $timestamps = false;

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'id_blog');
    }
}
