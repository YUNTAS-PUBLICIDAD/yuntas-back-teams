<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioRegistro extends Model
{
    use HasFactory;

    protected $table = 'usuario_registro';
    protected $primaryKey = 'id_userRegis';
    public $timestamps = false;

    protected $fillable = [
        'id_sec',
        'nombre',
        'correo',
        'celular',
        'fecha',
    ];
}
