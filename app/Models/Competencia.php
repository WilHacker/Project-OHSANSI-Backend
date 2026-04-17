<?php

namespace App\Models;

use App\Enums\EstadoCompetencia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
