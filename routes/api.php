<?php

use App\Http\Controllers\AccionDisponibilidadController;
use App\Http\Controllers\AccionSistemaController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AreaNivelController;
use App\Http\Controllers\AreaNivelGradoController;
use App\Http\Controllers\AreaOlimpiadaController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\CompetidorController;
use App\Http\Controllers\ConfiguracionAccionController;
use App\Http\Controllers\CronogramaFaseController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DescalificacionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\EvaluadorController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\FaseGlobalController;
use App\Http\Controllers\GradoEscolaridadController;
use App\Http\Controllers\ImportarcsvController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\ListaResponsableAreaController;
use App\Http\Controllers\MedalleroController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\OlimpiadaController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ResponsableController;
use App\Http\Controllers\RolAccionController;
use App\Http\Controllers\SistemaEstadoController;
use App\Http\Controllers\UsuarioAccionesController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Health checks (sin versión, sin autenticación)
|--------------------------------------------------------------------------
*/
Route::get('/test', function () {
    return response()->json([
        'mensaje'   => '¡OHSANSI Backend API funcionando correctamente!',
        'estado'    => 'activo',
        'timestamp' => now(),
    ]);
});

Route::get('/', function () {
    return response()->json(['mensaje' => 'API funcionando correctamente']);
});

/*
|--------------------------------------------------------------------------
| API v1
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Rutas públicas (sin autenticación)
    |----------------------------------------------------------------------
    */

    // Autenticación
    Route::prefix('auth')->group(function () {
        Route::post('login', [UsuarioController::class, 'login'])
            ->middleware('throttle:login');
        Route::middleware('auth:sanctum')->get('me', [UsuarioController::class, 'me']);
    });

    // Estado del sistema (usado por el frontend antes de login)
    Route::get('/sistema/estado', [SistemaEstadoController::class, 'index']);

    // Información pública de olimpiadas
    Route::get('/olimpiadas/actual', [OlimpiadaController::class, 'olimpiadaActual']);
    Route::get('/olimpiadas/anteriores', [OlimpiadaController::class, 'olimpiadasAnteriores']);
    Route::get('/gestiones', [OlimpiadaController::class, 'gestiones']);

    // Datos geográficos e institucionales (solo lectura pública)
    Route::get('/departamento', [ListaResponsableAreaController::class, 'getDepartamento']);
    Route::get('/generos', [ListaResponsableAreaController::class, 'getGenero']);
    Route::apiResource('departamentos', DepartamentoController::class)->only(['index']);
    Route::apiResource('instituciones', InstitucionController::class)->only(['index']);

    /*
    |----------------------------------------------------------------------
    | Rutas protegidas (requieren token Sanctum)
    |----------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        // Búsqueda de usuario por cédula de identidad
        Route::get('/usuarios/ci/{ci}', [UsuarioController::class, 'showByCi']);

        /*
        |------------------------------------------------------------------
        | COMPETENCIAS
        |------------------------------------------------------------------
        */
        Route::controller(CompetenciaController::class)->prefix('competencias')->group(function () {
            // CRUD
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');

            // Máquina de estados
            Route::patch('/{id}/publicar', 'publicar');
            Route::patch('/{id}/iniciar', 'iniciar');
            Route::post('/{id}/cerrar', 'cerrar');
            Route::post('/{id}/avalar', 'avalar');

            // Fases y áreas
            Route::get('/fase-global/clasificatoria/actuales', 'fasesClasificatorias');
            Route::get('/responsable/{id_user}/areas/actuales', 'areasResponsable');
            Route::get('/area/{id_area}/niveles', 'nivelesPorArea');

            // Filtros del dashboard
            Route::get('/responsable/{id_responsable}/area/{id_area}', 'indexPorResponsable');
            Route::get('/responsable/{id_user}/areas-niveles-competencia', 'areasNivelesCreados');
        });

        /*
        |------------------------------------------------------------------
        | EXÁMENES
        |------------------------------------------------------------------
        */
        Route::controller(ExamenController::class)->prefix('examenes')->group(function () {
            Route::get('/competencias/{competenciaId}', 'index');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
            Route::patch('/{id}/iniciar', 'iniciar');
            Route::patch('/{id}/finalizar', 'finalizar');
            Route::get('/area-nivel/{id_area_nivel}', 'indexPorAreaNivel');
            Route::get('/combo/area-nivel/{id}', 'comboPorAreaNivel');
            Route::get('/{id}/competidores', 'competidoresPorExamen');
        });

        /*
        |------------------------------------------------------------------
        | EVALUADORES
        |------------------------------------------------------------------
        */
        Route::get('/evaluadores/dashboard', [EvaluadorController::class, 'dashboard']);

        Route::prefix('evaluadores')->group(function () {
            Route::post('/', [EvaluadorController::class, 'store']);
            Route::get('/', [EvaluadorController::class, 'index']);
            Route::get('/{id}', [EvaluadorController::class, 'show']);
            Route::put('/ci/{ci}', [EvaluadorController::class, 'updateByCi']);
            Route::get('/{id}/areas-niveles', [EvaluadorController::class, 'getAreasNivelesById']);
            Route::post('/ci/{ci}/areas', [EvaluadorController::class, 'addAreasByCi']);
            Route::get('/ci/{ci}/gestiones', [EvaluadorController::class, 'getGestionesByCi']);
            Route::post('/ci/{ci}/asignaciones', [EvaluadorController::class, 'addAsignaciones']);
            Route::get('/ci/{ci}/gestion/{gestion}/areas', [EvaluadorController::class, 'getAreasByCiAndGestion']);
            Route::get('/{id}/asignaciones-agrupadas', [EvaluadorController::class, 'getAsignacionesAgrupadas']);
        });

        /*
        |------------------------------------------------------------------
        | SALA DE EVALUACIÓN
        |------------------------------------------------------------------
        */
        Route::prefix('sala-evaluacion')->controller(EvaluacionController::class)->group(function () {
            Route::get('/examen/{id_examen}', 'index');
            Route::post('/{id}/bloquear', 'bloquear');
            Route::post('/{id}/guardar', 'guardarNota');
            Route::post('/{id}/desbloquear', 'desbloquear');
            Route::post('/{id}/descalificar', 'descalificar');
            Route::get('/evaluador/{id_user}/areas-niveles', 'listarAreasNiveles');
        });

        /*
        |------------------------------------------------------------------
        | RESPONSABLES DE ÁREA
        |------------------------------------------------------------------
        */
        Route::prefix('responsables')->group(function () {
            Route::post('/', [ResponsableController::class, 'store']);
            Route::get('/', [ResponsableController::class, 'index']);
            Route::get('/{id}', [ResponsableController::class, 'show']);
            Route::get('/ci/{ci}/gestiones', [ResponsableController::class, 'getGestionesByCi']);
            Route::put('/ci/{ci}', [ResponsableController::class, 'updateByCi']);
            Route::post('/ci/{ci}/areas', [ResponsableController::class, 'addAreas']);
            Route::get('/ci/{ci}/gestion/{gestion}/areas', [ResponsableController::class, 'getAreasByCiAndGestion']);
            Route::get('/areas/ocupadas/gestion/actual', [ResponsableController::class, 'getOcupadasEnGestionActual']);
            Route::get('/{id_usuario}/areas-con-niveles/olimpiada-actual', [ResponsableController::class, 'areasConNivelesPorOlimpiadaActual']);
        });

        /*
        |------------------------------------------------------------------
        | OLIMPIADAS
        |------------------------------------------------------------------
        */
        Route::prefix('olimpiadas')->group(function () {
            Route::get('/', [OlimpiadaController::class, 'index']);
            Route::post('/', [OlimpiadaController::class, 'store']);
            Route::patch('/{id}/activar', [OlimpiadaController::class, 'activar']);
            Route::post('/admin', [OlimpiadaController::class, 'storeAdmin']);
        });

        /*
        |------------------------------------------------------------------
        | ESTRUCTURA: NIVELES, ÁREAS, GRADOS
        |------------------------------------------------------------------
        */
        Route::apiResource('niveles', NivelController::class)->only(['index', 'store']);
        Route::get('/niveles/{id_nivel}', [NivelController::class, 'show']);

        Route::get('/area', [AreaController::class, 'index']);
        Route::post('/area', [AreaController::class, 'store']);
        Route::get('/areas/actuales', [AreaController::class, 'getActualesPlanas']);

        Route::get('/area/gestion/{gestion}', [AreaOlimpiadaController::class, 'getAreasByGestion']);
        Route::get('/area/{id_olimpiada}', [AreaOlimpiadaController::class, 'getAreasByOlimpiada']);
        Route::get('/areas-gestion', [AreaOlimpiadaController::class, 'getAreasGestionActual']);
        Route::get('/areas-nombres', [AreaOlimpiadaController::class, 'getNombresAreasGestionActual']);

        Route::get('/grados-escolaridad', [GradoEscolaridadController::class, 'index']);
        Route::get('/grados-escolaridad/{id_grado_escolaridad}', [GradoEscolaridadController::class, 'show']);

        /*
        |------------------------------------------------------------------
        | ÁREA-NIVEL y ÁREA-NIVEL-GRADO
        | IMPORTANTE: rutas con segmentos literales SIEMPRE antes del
        | wildcard /{id_olimpiada} para evitar que lo tape.
        |------------------------------------------------------------------
        */

        // Rutas exactas (sin wildcard de ID) — PRIMERO
        Route::get('/area-nivel', [AreaNivelGradoController::class, 'index']);
        Route::post('/area-nivel', [AreaNivelGradoController::class, 'store']);
        Route::get('/area-nivel/actuales', [AreaNivelController::class, 'getActuales']);
        Route::get('/area-nivel/detalle', [AreaNivelController::class, 'getAllWithDetails']);
        Route::get('/area-nivel/sim/simplificado', [AreaNivelGradoController::class, 'getAreasConNivelesSimplificado']);
        Route::get('/areas-con-niveles', [AreaNivelGradoController::class, 'getAreasConNiveles']);
        Route::get('/area-niveles/{id_area}', [AreaNivelGradoController::class, 'getByAreaAll']);
        Route::post('/area-nivel/por-gestion', [AreaNivelGradoController::class, 'getByGestionAndAreas']);

        // Rutas con primer segmento literal + wildcard — ANTES del wildcard solo
        Route::get('/area-nivel/show/{id}', [AreaNivelController::class, 'show']);
        Route::get('/area-nivel/por-area/{id_area}', [AreaNivelController::class, 'getByArea']);
        Route::put('/area-nivel/por-area/{id_area}', [AreaNivelController::class, 'updateByArea']);
        Route::get('/area-nivel/gestion/{gestion}', [AreaNivelController::class, 'getAreasConNivelesPorGestion']);
        Route::get('/area-nivel/gestion/{gestion}/area/{id_area}', [AreaNivelGradoController::class, 'getNivelesGradosByAreaAndGestion']);
        Route::post('/area-nivel/gestion/{gestion}/areas', [AreaNivelGradoController::class, 'getNivelesGradosByAreasAndGestion']);
        Route::get('/area-nivel/olimpiada/{id_olimpiada}/area/{id_area}', [AreaNivelController::class, 'getNivelesPorAreaOlimpiada']);

        // Wildcard general — AL FINAL para no tapar nada
        Route::get('/area-nivel/{id_olimpiada}', [AreaNivelController::class, 'getAreasConNivelesPorOlimpiada']);
        Route::put('/area-nivel/{id}', [AreaNivelController::class, 'update']);

        /*
        |------------------------------------------------------------------
        | PARÁMETROS
        |------------------------------------------------------------------
        */
        Route::get('/parametros/gestion-actual', [ParametroController::class, 'getParametrosGestionActual']);
        Route::get('/parametros/gestiones', [ParametroController::class, 'getAllParametrosByGestiones']);
        Route::get('/parametros/an/area-niveles', [ParametroController::class, 'getParametrosByAreaNiveles']);
        Route::get('/parametros/{idOlimpiada}', [ParametroController::class, 'getByOlimpiada']);
        Route::post('/parametros', [ParametroController::class, 'store']);

        /*
        |------------------------------------------------------------------
        | LISTAS Y FILTROS
        |------------------------------------------------------------------
        */
        Route::get('/responsable/{idResponsable}', [ListaResponsableAreaController::class, 'getAreaPorResponsable']);
        Route::get('/niveles/{idArea}/area', [ListaResponsableAreaController::class, 'getNivelesPorArea']);
        Route::get('/grados/{idArea}/nivel/{idNivel}', [ListaResponsableAreaController::class, 'getListaGrados']);
        Route::get('/listaCompleta/{idResponsable}/{idArea}/{idNivel}/{idGrado}/{genero?}/{departamento?}', [ListaResponsableAreaController::class, 'listarPorAreaYNivel']);
        Route::get('/competencias/{id_competencia}/area/{idArea}/nivel/{idNivel}/competidores', [ListaResponsableAreaController::class, 'getCompetidoresPorAreaYNivel']);

        /*
        |------------------------------------------------------------------
        | MEDALLERO
        |------------------------------------------------------------------
        */
        Route::get('/responsableGestion/{idResponsable}', [MedalleroController::class, 'getAreaPorResponsable']);
        Route::get('/medallero/area/{idArea}/niveles', [MedalleroController::class, 'getNivelesPorArea']);
        Route::post('/medallero/configuracion', [MedalleroController::class, 'guardarMedallero']);

        /*
        |------------------------------------------------------------------
        | DESCALIFICACIONES
        |------------------------------------------------------------------
        */
        Route::get('/descalificados', [DescalificacionController::class, 'index']);
        Route::post('/descalificados', [DescalificacionController::class, 'store']);

        /*
        |------------------------------------------------------------------
        | REPORTES (AUDITORÍA Y RESULTADOS)
        |------------------------------------------------------------------
        */
        Route::prefix('reportes')->controller(ReporteController::class)->group(function () {
            Route::get('/historial-calificaciones', 'historialCalificaciones');
            Route::get('/competencia/{id}/ranking', 'ranking');
            Route::get('/evaluacion/{id}/historial', 'historialCambios');
            Route::get('/areas', 'getAreas');
            Route::get('/areas/{idArea}/niveles', 'getNivelesPorArea');
            Route::get('/competencia/{id}/exportar/certificados', 'exportarCertificados');
            Route::get('/competencia/{id}/exportar/ceremonia', 'exportarCeremonia');
            Route::get('/competencia/{id}/exportar/publicacion', 'exportarPublicacion');
            Route::get('/competencia/{id}/exportar/clasificados', 'exportarClasificados');
        });

        /*
        |------------------------------------------------------------------
        | IMPORTACIÓN CSV
        |------------------------------------------------------------------
        */
        Route::post('importar/{gestion}', [ImportarcsvController::class, 'importar']);

        /*
        |------------------------------------------------------------------
        | DEPARTAMENTOS E INSTITUCIONES (escritura protegida)
        |------------------------------------------------------------------
        */
        Route::apiResource('departamentos', DepartamentoController::class)->except(['index']);
        Route::apiResource('grados-escolaridad', GradoEscolaridadController::class);
        Route::apiResource('instituciones', InstitucionController::class)->except(['index']);

        /*
        |------------------------------------------------------------------
        | ROLES Y PERMISOS
        |------------------------------------------------------------------
        */
        Route::prefix('roles')->controller(RolAccionController::class)->group(function () {
            Route::get('/matriz', 'index');
            Route::post('/matriz', 'updateGlobal');

            // Acciones por rol individual
            Route::get('/{idRol}/acciones', 'index');
            Route::post('/{idRol}/acciones', 'store');
            Route::delete('/{idRol}/acciones/{idAccion}', 'destroy');
        });

        Route::get(
            'rol/{id_rol}/fase-global/{id_fase_global}/gestion/{id_gestion}',
            [AccionDisponibilidadController::class, 'index']
        );

        /*
        |------------------------------------------------------------------
        | CONFIGURACIÓN DE ACCIONES (disponibilidad por fase)
        |------------------------------------------------------------------
        */
        Route::prefix('configuracion-acciones')->controller(ConfiguracionAccionController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'update');
        });

        Route::get('/acciones-sistema', [AccionSistemaController::class, 'index']);
        Route::get('/usuario/mis-acciones/usuario/{id_user}', [UsuarioAccionesController::class, 'misAcciones']);

        /*
        |------------------------------------------------------------------
        | CRONOGRAMAS DE FASE
        |------------------------------------------------------------------
        */
        Route::controller(CronogramaFaseController::class)->prefix('cronograma-fases')->group(function () {
            Route::get('/actuales', 'listarActuales');
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

        /*
        |------------------------------------------------------------------
        | FASE GLOBAL
        |------------------------------------------------------------------
        */
        Route::controller(FaseGlobalController::class)->prefix('fase-global')->group(function () {
            Route::post('/configurar', 'storeCompleto');
            Route::get('/actuales', 'listarActuales');
            Route::get('/{id}', 'show');
            Route::patch('/{id}/cronograma', 'updateCronograma');
        });

    }); // fin auth:sanctum

    /*
    |----------------------------------------------------------------------
    | WebSockets — Autenticación de canales privados (Reverb)
    |----------------------------------------------------------------------
    | Reemplaza al /broadcasting/auth nativo con lógica personalizada
    | de autorización por rol. Requiere autenticación Sanctum.
    */
    Route::middleware('auth:sanctum')
        ->post('/broadcasting/auth', [BroadcastController::class, 'authenticate']);

}); // fin v1
