<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id_accion_sistema
 * @property string $codigo
 * @property string $nombre
 * @property string|null $descripcion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConfiguracionAccion> $configuraciones
 * @property-read int|null $configuraciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RolAccion> $rolAcciones
 * @property-read int|null $rol_acciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rol> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccionSistema newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccionSistema newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccionSistema query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccionSistema whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccionSistema whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccionSistema whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccionSistema whereIdAccionSistema($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccionSistema whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccionSistema whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function configuraciones(): HasMany
    {
        return $this->hasMany(ConfiguracionAccion::class, 'id_accion_sistema', 'id_accion_sistema');
    }

    public function rolAcciones(): HasMany
    {
        return $this->hasMany(RolAccion::class, 'id_accion_sistema', 'id_accion_sistema');
    }

    // Obtener los roles directamente asociados a esta acción del sistema
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'rol_accion', 'id_accion_sistema', 'id_rol')
                    ->withPivot('activo')
                    ->withTimestamps();
    }
}
