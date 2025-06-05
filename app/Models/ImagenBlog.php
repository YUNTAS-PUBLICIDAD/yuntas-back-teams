<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenBlog extends Model
{
     use HasFactory;
    
    protected $fillable = [
        'url_imagen',
        'parrafo_imagen',
        'id_blog'
    ];

    public $timestamps = true;

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'id_blog');
    }
}
