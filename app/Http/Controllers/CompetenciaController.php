<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\Competencia\StoreCompetenciaRequest;
use App\Http\Requests\Competencia\UpdateCompetenciaRequest;
use App\Http\Requests\Competencia\AvalarCompetenciaRequest;
use App\Http\Requests\Competencia\ConcluirCompetenciaRequest;
use App\Services\CompetenciaService;
use App\Services\CierreCompetenciaService;
use App\Repositories\CompetenciaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Events\CompetenciaCreada;
use App\Repositories\AreaRepository;
use App\Repositories\FaseGlobalRepository;

class CompetenciaController extends Controller
{
    public function __construct(
        protected CompetenciaService $service,
        protected CierreCompetenciaService $cierreService,
        protected CompetenciaRepository $repository,
        protected AreaRepository $areaRepo,
        protected FaseGlobalRepository $faseRepo
    ) {}

    /**
     * Listar competencias paginadas.
     *
     * Query param: ?por_pagina=15 (predeterminado)
     */
    public function index(Request $request): JsonResponse
    {
        $porPagina = $request->integer('por_pagina', 15);
        return response()->json($this->repository->getAll($porPagina));
    }

    /**
     * Crear una nueva competencia (Estado: Borrador).
     */
    public function store(StoreCompetenciaRequest $request): JsonResponse
    {
        $competencia = $this->service->crear($request->validated());
        broadcast(new CompetenciaCreada($competencia))->toOthers();

        return response()->json([
            'message' => 'Competencia creada exitosamente.',
            'data'    => $competencia,
        ], 201);
    }

    /**
     * Ver detalles completos (incluye exámenes y estado).
     */
    public function show(int $id): JsonResponse
    {
        $competencia = $this->repository->findWithFullHierarchy($id);

        if (!$competencia) {
            return response()->json(['mensaje' => 'Competencia no encontrada.'], 404);
        }

        return response()->json($competencia);
    }

    /**
     * Editar configuración (Solo si está en Borrador).
     */
    public function update(UpdateCompetenciaRequest $request, int $id): JsonResponse
    {
        $competencia = $this->service->actualizar($id, $request->validated());

        return response()->json([
            'message' => 'Competencia actualizada correctamente.',
            'data'    => $competencia,
        ]);
    }

    /**
     * Eliminar competencia (Solo si está en Borrador).
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->eliminar($id);

        return response()->json(['message' => 'Competencia eliminada correctamente.']);
    }

    /**
     * Publicar (Hacer visible en agenda).
     * Valida que existan exámenes y ponderaciones al 100%.
     */
    public function publicar(int $id): JsonResponse
    {
        $competencia = $this->service->publicar($id);

        return response()->json([
            'message' => 'Competencia publicada. Ahora es visible.',
            'data'    => $competencia,
        ]);
    }

    /**
     * Iniciar (Activar operación).
     * Permite que los exámenes individuales puedan abrirse.
     */
    public function iniciar(int $id): JsonResponse
    {
        $competencia = $this->service->iniciar($id);

        return response()->json([
            'message' => 'Competencia iniciada (En Proceso).',
            'data'    => $competencia,
        ]);
    }

    /**
     * Cerrar y Calcular (El Gran Final).
     * Calcula promedios ponderados y asigna medallas.
     */
    public function cerrar(ConcluirCompetenciaRequest $request, int $id): JsonResponse
    {
        $competencia = $this->cierreService->concluirYCalcular($id);

        return response()->json([
            'message' => 'Competencia concluida. Resultados calculados y medallas asignadas.',
            'data'    => $competencia,
        ]);
    }

    /**
     * Avalar (Firma Digital).
     * Congela los resultados para siempre.
     */
    public function avalar(AvalarCompetenciaRequest $request, int $id): JsonResponse
    {
        $competencia = $this->cierreService->avalar($id, auth()->id());

        return response()->json([
            'message' => 'Resultados avalados oficialmente.',
            'data'    => $competencia,
        ]);
    }

    /**
     * Lista competencias filtradas por Responsable y Área.
     */
    public function indexPorResponsable(int $idResponsable, int $idArea): JsonResponse
    {
        $competencias = $this->repository->getByResponsableAndArea($idResponsable, $idArea);

        return response()->json($competencias);
    }

    public function fasesClasificatorias(): JsonResponse
    {
        return response()->json($this->faseRepo->getClasificatoriasActuales());
    }

    public function areasResponsable(int $idUsuario): JsonResponse
    {
        return response()->json($this->areaRepo->getByResponsableActual($idUsuario));
    }

    public function nivelesPorArea(int $idArea): JsonResponse
    {
        return response()->json($this->service->listarNivelesPorArea($idArea));
    }

    /**
     * Áreas y Niveles de competencias ya creadas para un responsable.
     */
    public function areasNivelesCreados(int $idUsuario): JsonResponse
    {
        return response()->json($this->service->agruparAreasNivelesPorResponsable($idUsuario));
    }
}
