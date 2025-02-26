<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    
    /**
     * Obtiene la sección a la que pertenece este registro de usuario
     */
    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class, 'id_sec', 'id_sec');
    }
}
