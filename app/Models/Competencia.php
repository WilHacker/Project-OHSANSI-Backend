<?php

namespace App\Models;

use App\Enums\EstadoCompetencia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id_competencia
 * @property int|null $id_fase_global
 * @property int|null $id_area_nivel
 * @property \Illuminate\Support\Carbon $fecha_inicio
 * @property \Illuminate\Support\Carbon $fecha_fin
 * @property string $estado_fase
 * @property string $criterio_clasificacion
 * @property int|null $id_usuario_aval
 * @property \Illuminate\Support\Carbon|null $fecha_aval
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaNivel|null $areaNivel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Examen> $examenes
 * @property-read int|null $examenes_count
 * @property-read \App\Models\FaseGlobal|null $faseGlobal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medallero> $medalleros
 * @property-read int|null $medalleros_count
 * @property-read \App\Models\Usuario|null $usuarioAval
 * @method static Builder<static>|Competencia avalada()
 * @method static Builder<static>|Competencia borrador()
 * @method static Builder<static>|Competencia concluida()
 * @method static Builder<static>|Competencia enProceso()
 * @method static Builder<static>|Competencia newModelQuery()
 * @method static Builder<static>|Competencia newQuery()
 * @method static Builder<static>|Competencia publicada()
 * @method static Builder<static>|Competencia query()
 * @method static Builder<static>|Competencia whereCreatedAt($value)
 * @method static Builder<static>|Competencia whereCriterioClasificacion($value)
 * @method static Builder<static>|Competencia whereEstadoFase($value)
 * @method static Builder<static>|Competencia whereFechaAval($value)
 * @method static Builder<static>|Competencia whereFechaFin($value)
 * @method static Builder<static>|Competencia whereFechaInicio($value)
 * @method static Builder<static>|Competencia whereIdAreaNivel($value)
 * @method static Builder<static>|Competencia whereIdCompetencia($value)
 * @method static Builder<static>|Competencia whereIdFaseGlobal($value)
 * @method static Builder<static>|Competencia whereIdUsuarioAval($value)
 * @method static Builder<static>|Competencia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Competencia extends Model
{
    use HasFactory;

    protected $table = 'competencia';
    protected $primaryKey = 'id_competencia';

    protected $fillable = [
        'id_fase_global',
        'id_area_nivel',
        'fecha_inicio',
        'fecha_fin',
        'estado_fase',
        'criterio_clasificacion',
        'id_usuario_aval',
        'fecha_aval',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_aval' => 'datetime',
    ];

    public function faseGlobal(): BelongsTo
    {
        return $this->belongsTo(FaseGlobal::class, 'id_fase_global', 'id_fase_global');
    }

    public function areaNivel(): BelongsTo
    {
        return $this->belongsTo(AreaNivel::class, 'id_area_nivel', 'id_area_nivel');
    }

    public function examenes(): HasMany
    {
        return $this->hasMany(Examen::class, 'id_competencia', 'id_competencia');
    }

    public function usuarioAval(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_aval', 'id_usuario');
    }

    public function medalleros(): HasMany
    {
        return $this->hasMany(Medallero::class, 'id_competencia', 'id_competencia');
    }

    public function scopeBorrador(Builder $query): Builder
    {
        return $query->where('estado_fase', EstadoCompetencia::Borrador->value);
    }

    public function scopePublicada(Builder $query): Builder
    {
        return $query->where('estado_fase', EstadoCompetencia::Publicada->value);
    }

    public function scopeEnProceso(Builder $query): Builder
    {
        return $query->where('estado_fase', EstadoCompetencia::EnProceso->value);
    }

    public function scopeConcluida(Builder $query): Builder
    {
        return $query->where('estado_fase', EstadoCompetencia::Concluida->value);
    }

    public function scopeAvalada(Builder $query): Builder
    {
        return $query->where('estado_fase', EstadoCompetencia::Avalada->value);
    }
}
