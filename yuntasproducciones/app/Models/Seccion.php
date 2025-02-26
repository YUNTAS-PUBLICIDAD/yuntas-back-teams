<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'seccion';
    protected $primaryKey = 'id_sec';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'id_com',
    ];
}
