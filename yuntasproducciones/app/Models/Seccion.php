<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    
    /**
     * Obtiene todos los usuarios registrados en esta sección
     */
    public function usuariosRegistro(): HasMany
    {
        return $this->hasMany(UsuarioRegistro::class, 'id_sec', 'id_sec');
    }
}
