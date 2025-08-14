<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogEtiqueta extends Model
{
    use HasFactory;

    protected $table = 'blog_etiquetas';

    protected $fillable = [
        'blog_id',
        'meta_titulo',
        'meta_descripcion',
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }
}
