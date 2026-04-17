<?php

namespace App\Models;

use App\Enums\EstadoExamen;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id_examen
 * @property int|null $id_competencia
 * @property string $nombre
 * @property numeric $ponderacion
 * @property numeric $maxima_nota
 * @property \Illuminate\Support\Carbon|null $fecha_hora_inicio
 * @property string|null $tipo_regla
 * @property array<array-key, mixed>|null $configuracion_reglas
 * @property string $estado_ejecucion
 * @property \Illuminate\Support\Carbon|null $fecha_inicio_real
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Competencia|null $competencia
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Evaluacion> $evaluaciones
 * @property-read int|null $evaluaciones_count
 * @method static Builder<static>|Examen enCurso()
 * @method static Builder<static>|Examen finalizada()
 * @method static Builder<static>|Examen newModelQuery()
 * @method static Builder<static>|Examen newQuery()
 * @method static Builder<static>|Examen noIniciada()
 * @method static Builder<static>|Examen query()
 * @method static Builder<static>|Examen whereConfiguracionReglas($value)
 * @method static Builder<static>|Examen whereCreatedAt($value)
 * @method static Builder<static>|Examen whereEstadoEjecucion($value)
 * @method static Builder<static>|Examen whereFechaHoraInicio($value)
 * @method static Builder<static>|Examen whereFechaInicioReal($value)
 * @method static Builder<static>|Examen whereIdCompetencia($value)
 * @method static Builder<static>|Examen whereIdExamen($value)
 * @method static Builder<static>|Examen whereMaximaNota($value)
 * @method static Builder<static>|Examen whereNombre($value)
 * @method static Builder<static>|Examen wherePonderacion($value)
 * @method static Builder<static>|Examen whereTipoRegla($value)
 * @method static Builder<static>|Examen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Examen extends Model
{
    use HasFactory;

    protected $table = 'examen';
    protected $primaryKey = 'id_examen';

    protected $fillable = [
        'id_competencia',
        'nombre',
        'ponderacion',
        'maxima_nota',
        'fecha_hora_inicio',
        'tipo_regla',
        'configuracion_reglas',
        'estado_ejecucion',
        'fecha_inicio_real',
    ];

    protected $casts = [
        'ponderacion' => 'decimal:2',
        'maxima_nota' => 'decimal:2',
        'fecha_hora_inicio' => 'datetime',
        'fecha_inicio_real' => 'datetime',
        'configuracion_reglas' => 'array',
    ];

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class, 'id_competencia', 'id_competencia');
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(Evaluacion::class, 'id_examen', 'id_examen');
    }

    public function scopeNoIniciada(Builder $query): Builder
    {
        return $query->where('estado_ejecucion', EstadoExamen::NoIniciada->value);
    }

    public function scopeEnCurso(Builder $query): Builder
    {
        return $query->where('estado_ejecucion', EstadoExamen::EnCurso->value);
    }

    public function scopeFinalizada(Builder $query): Builder
    {
        return $query->where('estado_ejecucion', EstadoExamen::Finalizada->value);
    }
}
