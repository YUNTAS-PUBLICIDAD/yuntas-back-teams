<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogFooter extends Model
{
    use HasFactory;

    protected $table = 'blog_footers';
    protected $primaryKey = 'id_blog_footer';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'descripcion',
        'public_image1',
        'url_image1',
        'public_image2',
        'url_image2',
        'public_image3',
        'url_image3',
    ];

    public function blog(){
        return $this->belongsTo(Blog::class, 'id_blog_footer', 'id_blog_footer');
    }
}
