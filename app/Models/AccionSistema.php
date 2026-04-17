<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccionSistema extends Model
{
    use HasFactory;

    protected $table = 'accion_sistema';
    protected $primaryKey = 'id_accion_sistema';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
    ];

    public function configuraciones()
    {
        return $this->hasMany(ConfiguracionAccion::class, 'id_accion_sistema', 'id_accion_sistema');
    }

    public function rolAcciones()
    {
        return $this->hasMany(RolAccion::class, 'id_accion_sistema', 'id_accion_sistema');
    }

    // Obtener los roles directamente asociados a esta acción del sistema
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_accion', 'id_accion_sistema', 'id_rol')
                    ->withPivot('activo')
                    ->withTimestamps();
    }
}
