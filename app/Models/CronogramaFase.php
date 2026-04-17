<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id_cronograma_fase
 * @property int|null $id_fase_global
 * @property \Illuminate\Support\Carbon $fecha_inicio
 * @property \Illuminate\Support\Carbon $fecha_fin
 * @property int|null $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FaseGlobal|null $faseGlobal
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase whereFechaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase whereIdCronogramaFase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase whereIdFaseGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CronogramaFase whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CronogramaFase extends Model
{
    use HasFactory;

    protected $table = 'cronograma_fase';
    protected $primaryKey = 'id_cronograma_fase';

    protected $fillable = [
        'id_fase_global',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
        'estado'       => 'integer',
        'id_fase_global' => 'integer',
    ];

    /**
     * Formato de serialización para arrays/JSON.
     * Garantiza que el frontend reciba "YYYY-MM-DD HH:mm:ss"
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function faseGlobal(): BelongsTo
    {
        return $this->belongsTo(FaseGlobal::class, 'id_fase_global', 'id_fase_global');
    }
}
