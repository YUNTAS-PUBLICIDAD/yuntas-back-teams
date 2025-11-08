<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogParrafo extends Model
{
    protected $table = 'blog_parrafos';
    protected $fillable = ['blog_id', 'parrafo', 'orden'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
