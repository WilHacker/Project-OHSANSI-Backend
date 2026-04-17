<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
	class AccionSistema extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_archivo_csv
 * @property string $nombre
 * @property \Illuminate\Support\Carbon $fecha
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereIdArchivoCsv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArchivoCsv whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ArchivoCsv extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_area
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaOlimpiada> $areaOlimpiadas
 * @property-read int|null $area_olimpiadas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Olimpiada> $olimpiadas
 * @property-read int|null $olimpiadas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereIdArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Area whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\AreaOlimpiada|null $areaOlimpiada
 */
	class Area extends \Eloquent {}
}

namespace App\Models{
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
	class AreaNivel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_area_nivel
 * @property int $id_grado_escolaridad
 * @property-read \App\Models\AreaNivel $areaNivel
 * @property-read \App\Models\GradoEscolaridad $gradoEscolaridad
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaNivelGrado whereIdGradoEscolaridad($value)
 * @mixin \Eloquent
 */
	class AreaNivelGrado extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_area_olimpiada
 * @property int|null $id_area
 * @property int|null $id_olimpiada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Area|null $area
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaNivel> $areaNiveles
 * @property-read int|null $area_niveles_count
 * @property-read \App\Models\Olimpiada|null $olimpiada
 * @property-read \App\Models\ResponsableArea|null $responsableArea
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereIdArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereIdAreaOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereIdOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AreaOlimpiada whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class AreaOlimpiada extends \Eloquent {}
}

namespace App\Models{
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
	class Competencia extends \Eloquent {}
}

namespace App\Models{
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
	class Competidor extends \Eloquent {}
}

namespace App\Models{
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
	class ConfiguracionAccion extends \Eloquent {}
}

namespace App\Models{
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
	class CronogramaFase extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_departamento
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento whereIdDepartamento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Departamento extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_descalificacion
 * @property int $id_competidor
 * @property string $observaciones
 * @property \Illuminate\Support\Carbon $fecha_descalificacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Competidor $competidor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereFechaDescalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereIdCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereIdDescalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DescalificacionAdministrativa whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class DescalificacionAdministrativa extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_evaluacion
 * @property int|null $id_competidor
 * @property int|null $id_examen
 * @property numeric $nota
 * @property string $estado_participacion
 * @property string|null $observacion
 * @property string|null $resultado_calculado
 * @property int|null $bloqueado_por
 * @property \Illuminate\Support\Carbon|null $fecha_bloqueo
 * @property bool $esta_calificado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Competidor|null $competidor
 * @property-read mixed $es_zombie
 * @property-read \App\Models\Examen|null $examen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogCambioNota> $logsCambios
 * @property-read int|null $logs_cambios_count
 * @property-read mixed $nombre_juez_bloqueo
 * @property-read \App\Models\Usuario|null $usuarioBloqueo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereBloqueadoPor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereEstaCalificado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereEstadoParticipacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereFechaBloqueo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereIdCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereIdEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereIdExamen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereResultadoCalculado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evaluacion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Evaluacion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_evaluador_an
 * @property int|null $id_usuario
 * @property int|null $id_area_nivel
 * @property bool $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaNivel|null $areaNivel
 * @property-read \App\Models\Usuario|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereIdEvaluadorAn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EvaluadorAn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class EvaluadorAn extends \Eloquent {}
}

namespace App\Models{
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
	class Examen extends \Eloquent {}
}

namespace App\Models{
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
	class FaseGlobal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_grado_escolaridad
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaNivel> $areaNiveles
 * @property-read int|null $area_niveles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad whereIdGradoEscolaridad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradoEscolaridad whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class GradoEscolaridad extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_grupo
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GrupoCompetidor> $grupoCompetidores
 * @property-read int|null $grupo_competidores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereIdGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Grupo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Grupo extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_grupo_competidor
 * @property int|null $id_grupo
 * @property int|null $id_competidor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Competidor|null $competidor
 * @property-read \App\Models\Grupo|null $grupo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereIdCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereIdGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereIdGrupoCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrupoCompetidor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class GrupoCompetidor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_institucion
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion whereIdInstitucion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Institucion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Institucion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_log_cambio_nota
 * @property int|null $id_evaluacion
 * @property int $id_usuario_autor
 * @property numeric $nota_nueva
 * @property numeric $nota_anterior
 * @property string $motivo_cambio
 * @property \Illuminate\Support\Carbon $fecha_cambio
 * @property-read \App\Models\Usuario $autor
 * @property-read \App\Models\Evaluacion|null $evaluacion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereFechaCambio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereIdEvaluacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereIdLogCambioNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereIdUsuarioAutor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereMotivoCambio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereNotaAnterior($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogCambioNota whereNotaNueva($value)
 * @mixin \Eloquent
 */
	class LogCambioNota extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_medallero
 * @property int|null $id_competidor
 * @property int|null $id_competencia
 * @property int $puesto
 * @property string $medalla
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Competencia|null $competencia
 * @property-read \App\Models\Competidor|null $competidor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereIdCompetencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereIdCompetidor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereIdMedallero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereMedalla($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero wherePuesto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medallero whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Medallero extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_nivel
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaNivel> $areaNiveles
 * @property-read int|null $area_niveles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel whereIdNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nivel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Nivel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_olimpiada
 * @property string|null $nombre
 * @property string $gestion
 * @property bool $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AreaOlimpiada> $areaOlimpiadas
 * @property-read int|null $area_olimpiadas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Area> $areas
 * @property-read int|null $areas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FaseGlobal> $faseGlobales
 * @property-read int|null $fase_globales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsuarioRol> $usuarioRoles
 * @property-read int|null $usuario_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereGestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereIdOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Olimpiada whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Olimpiada extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_parametro
 * @property int|null $id_area_nivel
 * @property numeric|null $nota_min_aprobacion
 * @property int|null $cantidad_maxima
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaNivel|null $areaNivel
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereCantidadMaxima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereIdParametro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereNotaMinAprobacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Parametro whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Parametro extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_param_medallero
 * @property int|null $id_area_nivel
 * @property int|null $oro
 * @property int|null $plata
 * @property int|null $bronce
 * @property int|null $mencion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaNivel|null $areaNivel
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereBronce($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereIdAreaNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereIdParamMedallero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereMencion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereOro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero wherePlata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ParametroMedallero whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ParametroMedallero extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_persona
 * @property string $nombre
 * @property string $apellido
 * @property string $ci
 * @property string $telefono
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Competidor> $competidores
 * @property-read int|null $competidores_count
 * @property-read \App\Models\Usuario|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereApellido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereCi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereIdPersona($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Persona whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Persona extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_responsable_area
 * @property int|null $id_usuario
 * @property int|null $id_area_olimpiada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AreaOlimpiada|null $areaOlimpiada
 * @property-read \App\Models\Usuario|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereIdAreaOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereIdResponsableArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResponsableArea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ResponsableArea extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_rol
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AccionSistema> $accionesSistema
 * @property-read int|null $acciones_sistema_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RolAccion> $rolAcciones
 * @property-read int|null $rol_acciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsuarioRol> $usuarioRoles
 * @property-read int|null $usuario_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Usuario> $usuarios
 * @property-read int|null $usuarios_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereIdRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rol whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Rol extends \Eloquent {}
}

namespace App\Models{
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
	class RolAccion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_usuario
 * @property int|null $id_persona
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EvaluadorAn> $evaluadoresAn
 * @property-read int|null $evaluadores_an_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Persona|null $persona
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResponsableArea> $responsableAreas
 * @property-read int|null $responsable_areas_count
 * @property-read \App\Models\UsuarioRol|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rol> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsuarioRol> $usuarioRoles
 * @property-read int|null $usuario_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereIdPersona($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Usuario whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Usuario extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_usuario_rol
 * @property int|null $id_usuario
 * @property int|null $id_rol
 * @property int|null $id_olimpiada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Olimpiada|null $olimpiada
 * @property-read \App\Models\Rol|null $rol
 * @property-read \App\Models\Usuario|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdOlimpiada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereIdUsuarioRol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsuarioRol whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class UsuarioRol extends \Eloquent {}
}

