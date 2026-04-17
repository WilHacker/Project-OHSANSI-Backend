<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id_rol
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AccionSistema> $accionesSistema
 * @property-read int|null $acciones_sistema_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RolAccion> $rolAcciones
 * @property-read int|null $rol_acciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsuarioRol> $usuarioRoles
 * @property-read int|null $usuario_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Usuario> $usuarios
 * @property-read int|null $usuarios_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereIdRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rol extends Model
{
    use HasFactory;

    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = true;

    protected $fillable = [
        'nombre'
    ];

    public function usuarioRoles(): HasMany
    {
        return $this->hasMany(UsuarioRol::class, 'id_rol');
    }

    public function rolAcciones(): HasMany
    {
        return $this->hasMany(RolAccion::class, 'id_rol');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'usuario_rol', 'id_rol', 'id_usuario');
    }

    public function accionesSistema(): BelongsToMany
    {
        return $this->belongsToMany(AccionSistema::class, 'rol_accion', 'id_rol', 'id_accion_sistema');
    }
}
