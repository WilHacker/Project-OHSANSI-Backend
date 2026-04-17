<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_configuracion_accion
 * @property int|null $id_accion_sistema
 * @property int|null $id_fase_global
 * @property bool $habilitada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccionSistema|null $accionSistema
 * @property-read \App\Models\FaseGlobal|null $faseGlobal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConfiguracionAccion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConfiguracionAccion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConfiguracionAccion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConfiguracionAccion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConfiguracionAccion whereHabilitada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConfiguracionAccion whereIdAccionSistema($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConfiguracionAccion whereIdConfiguracionAccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConfiguracionAccion whereIdFaseGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConfiguracionAccion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConfiguracionAccion extends Model
{
    use HasFactory;

    protected $table = 'configuracion_accion';
    protected $primaryKey = 'id_configuracion_accion';
    public $timestamps = true;

    protected $fillable = [
        'id_accion_sistema',
        'id_fase_global',
        'habilitada',
    ];

    protected $casts = [
        'habilitada' => 'boolean',
    ];

    public function faseGlobal(): BelongsTo
    {
        return $this->belongsTo(FaseGlobal::class, 'id_fase_global', 'id_fase_global');
    }

    public function accionSistema(): BelongsTo
    {
        return $this->belongsTo(AccionSistema::class, 'id_accion_sistema', 'id_accion_sistema');
    }
}
