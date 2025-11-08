<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogBeneficio extends Model
{
    protected $table = 'blog_beneficios';
    protected $fillable = ['blog_id', 'beneficio', 'orden'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
