<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id_fase_global
 * @property int|null $id_olimpiada
 * @property string $codigo
 * @property string $nombre
 * @property int $orden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competencia> $competencias
 * @property-read int|null $competencias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConfiguracionAccion> $configuraciones
 * @property-read int|null $configuraciones_count
 * @property-read \App\Models\CronogramaFase|null $cronograma
 * @property-read \App\Models\Olimpiada|null $olimpiada
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal whereCodigo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal whereIdFaseGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal whereIdOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal whereOrden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaseGlobal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FaseGlobal extends Model
{
    use HasFactory;

    protected $table = 'fase_global';
    protected $primaryKey = 'id_fase_global';

    protected $fillable = [
        'id_olimpiada',
        'codigo',
        'nombre',
        'orden',
    ];

    protected $casts = [
        'orden'        => 'integer',
        'id_olimpiada' => 'integer',
    ];

    public function olimpiada(): BelongsTo
    {
        return $this->belongsTo(Olimpiada::class, 'id_olimpiada', 'id_olimpiada');
    }

    public function cronograma(): HasOne
    {
        return $this->hasOne(CronogramaFase::class, 'id_fase_global', 'id_fase_global');
    }

    public function configuraciones(): HasMany
    {
        return $this->hasMany(ConfiguracionAccion::class, 'id_fase_global', 'id_fase_global');
    }

    public function competencias(): HasMany
    {
        return $this->hasMany(Competencia::class, 'id_fase_global', 'id_fase_global');
    }
}
