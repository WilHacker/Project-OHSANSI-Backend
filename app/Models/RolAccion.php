<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_rol_accion
 * @property int|null $id_rol
 * @property int|null $id_accion_sistema
 * @property bool|null $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccionSistema|null $accionSistema
 * @property-read \App\Models\Rol|null $rol
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolAccion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolAccion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolAccion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolAccion whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolAccion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolAccion whereIdAccionSistema($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolAccion whereIdRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolAccion whereIdRolAccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RolAccion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RolAccion extends Model
{
    use HasFactory;

    protected $table = 'rol_accion';
    protected $primaryKey = 'id_rol_accion';
    public $timestamps = true;
    
    protected $fillable = [
        'id_rol',
        'id_accion_sistema',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function accionSistema(): BelongsTo
    {
        return $this->belongsTo(AccionSistema::class, 'id_accion_sistema', 'id_accion_sistema');
    }
}
