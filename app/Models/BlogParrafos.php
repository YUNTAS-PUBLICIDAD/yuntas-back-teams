<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Blog;

class BlogParrafos extends Model
{
    //
    protected $table = 'blogs_parrafos';
    protected $fillable = [
        'parrafo',
        'blog_id'
    ];

    public $timestamps = true;
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }   
}
