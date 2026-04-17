<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property int $id_area_nivel
 * @property int|null $id_area_olimpiada
 * @property int|null $id_nivel
 * @property bool|null $es_activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Area|null $area
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaNivelGrado> $areaNivelGrados
 * @property-read int|null $area_nivel_grados_count
 * @property-read \App\Models\AreaOlimpiada|null $areaOlimpiada
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competencia> $competencias
 * @property-read int|null $competencias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EvaluadorAn> $evaluadoresAn
 * @property-read int|null $evaluadores_an_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GradoEscolaridad> $gradosEscolaridad
 * @property-read int|null $grados_escolaridad_count
 * @property-read \App\Models\Nivel|null $nivel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ParametroMedallero> $paramMedalleros
 * @property-read int|null $param_medalleros_count
 * @property-read \App\Models\Parametro|null $parametro
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivel whereEsActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivel whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivel whereIdAreaOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivel whereIdNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AreaNivel extends Model
{
    use HasFactory;

    protected $table = 'area_nivel';
    protected $primaryKey = 'id_area_nivel';
    public $timestamps = true;

    protected $fillable = [
        'id_area_olimpiada',
        'id_nivel',
        'es_activo',
    ];

    protected $casts = [
        'es_activo' => 'boolean',
    ];

    public function areaOlimpiada(): BelongsTo
    {
        return $this->belongsTo(AreaOlimpiada::class, 'id_area_olimpiada', 'id_area_olimpiada');
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id_nivel');
    }

    public function parametro(): HasOne
    {
        return $this->hasOne(Parametro::class, 'id_area_nivel', 'id_area_nivel');
    }

    public function competidores(): HasMany
    {
        return $this->hasMany(Competidor::class, 'id_area_nivel');
    }

    public function competencias(): HasMany
    {
        return $this->hasMany(Competencia::class, 'id_area_nivel');
    }
    // Relación a través de AreaOlimpiada para llegar al Área
    public function area(): HasOneThrough
    {
        return $this->hasOneThrough(Area::class, AreaOlimpiada::class, 'id_area_olimpiada', 'id_area', 'id_area_olimpiada', 'id_area');
    }

    public function evaluadoresAn(): HasMany
    {
        return $this->hasMany(EvaluadorAn::class, 'id_area_nivel');
    }

    public function paramMedalleros(): HasMany
    {
        return $this->hasMany(ParametroMedallero::class, 'id_area_nivel');
    }

    public function areaNivelGrados(): HasMany
    {
        return $this->hasMany(AreaNivelGrado::class, 'id_area_nivel');
    }

    public function gradosEscolaridad(): BelongsToMany
    {
        return $this->belongsToMany(GradoEscolaridad::class, 'area_nivel_grado', 'id_area_nivel', 'id_grado_escolaridad');
    }

}
