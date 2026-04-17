<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id_competidor
 * @property int|null $id_archivo_csv
 * @property int|null $id_institucion
 * @property int|null $id_departamento
 * @property int|null $id_area_nivel
 * @property int|null $id_persona
 * @property int|null $id_grado_escolaridad
 * @property string|null $contacto_tutor
 * @property string|null $genero
 * @property string $estado_evaluacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ArchivoCsv|null $archivoCsv
 * @property-read \App\Models\AreaNivel|null $areaNivel
 * @property-read \App\Models\Departamento|null $departamento
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Evaluacion> $evaluaciones
 * @property-read int|null $evaluaciones_count
 * @property-read \App\Models\GradoEscolaridad|null $gradoEscolaridad
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GrupoCompetidor> $grupoCompetidores
 * @property-read int|null $grupo_competidores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grupo> $grupos
 * @property-read int|null $grupos_count
 * @property-read \App\Models\Institucion|null $institucion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medallero> $medalleros
 * @property-read int|null $medalleros_count
 * @property-read \App\Models\Persona|null $persona
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereContactoTutor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereEstadoEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereGenero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereIdArchivoCsv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereIdCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereIdDepartamento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereIdGradoEscolaridad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereIdInstitucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereIdPersona($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Competidor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Competidor extends Model
{
    use HasFactory;

    protected $table = 'competidor';
    protected $primaryKey = 'id_competidor';
    public $timestamps = true;

    protected $fillable = [
        'id_archivo_csv',
        'id_institucion',
        'id_departamento',
        'id_area_nivel',
        'id_persona',
        'id_grado_escolaridad',
        'contacto_tutor',
        'tutor_academico',
        'genero',
        'estado_evaluacion',
    ];

    protected $attributes = [
        'estado_evaluacion' => 'disponible',
    ];

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id_institucion');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    public function areaNivel(): BelongsTo
    {
        return $this->belongsTo(AreaNivel::class, 'id_area_nivel', 'id_area_nivel');
    }

    public function gradoEscolaridad(): BelongsTo
    {
        return $this->belongsTo(GradoEscolaridad::class, 'id_grado_escolaridad', 'id_grado_escolaridad');
    }

    public function archivoCsv(): BelongsTo
    {
        return $this->belongsTo(ArchivoCsv::class, 'id_archivo_csv', 'id_archivo_csv');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function grupoCompetidores(): HasMany
    {
        return $this->hasMany(GrupoCompetidor::class, 'id_competidor', 'id_competidor');
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(Evaluacion::class, 'id_competidor', 'id_competidor');
    }

    public function medalleros(): HasMany
    {
        return $this->hasMany(Medallero::class, 'id_competidor', 'id_competidor');
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'grupo_competidor', 'id_competidor', 'id_grupo')
                    ->withTimestamps();
    }
}
