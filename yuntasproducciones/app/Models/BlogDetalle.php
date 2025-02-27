<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogDetalle extends Model
{
    use HasFactory;

    protected $table = 'blog_detalle';
    protected $primaryKey = 'id_blog_detalle';
    public $timestamps = false;
    protected $fillable = [
        'id_blog',
        'descripcion',
        'parrafo_01',
        'parrafo_02',
        'parrafo_03',
        'img_01',
        'img_02',
        'img_03',
    ];
}
