<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Blog extends Model
{
    use HasFactory;
    protected $table = 'blogs';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'producto_id',
        'link',
        'titulo',
        'parrafo',
        'descripcion',
        'imagen_principal'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
    public function imagenes()
    {
        return $this->hasMany(ImagenBlog::class, 'id_blog');
    }

    public function video()
    {
        return $this->hasOne(VideoBlog::class, 'id_blog');
    }

    public function detalle()
    {
        return $this->hasOne(DetalleBlog::class, 'id_blog');
    }
}
