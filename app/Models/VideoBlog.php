<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class VideoBlog extends Model
{
     use HasFactory;

    protected $fillable = [
        'url_video',
        'titulo_video',
        'id_blog'
    ];

    public $timestamps = true;

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'id_blog');
    }
}
