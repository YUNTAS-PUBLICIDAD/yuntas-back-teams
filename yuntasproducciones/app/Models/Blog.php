<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    /** @use HasFactory<\Database\Factories\BlogFactory> */
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen_principal',
        'estatus',
        'fecha_creacion',
        'fecha_actualizacion'
    ];

    public $timestamps = false;
}
