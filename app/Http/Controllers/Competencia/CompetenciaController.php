<?php

namespace App\Http\Controllers\Competencia;

use Illuminate\Routing\Controller;
use App\Http\Requests\Competencia\StoreCompetenciaRequest;
use App\Http\Requests\Competencia\UpdateCompetenciaRequest;
use App\Http\Requests\Competencia\AvalarCompetenciaRequest;
use App\Http\Requests\Competencia\ConcluirCompetenciaRequest;
use App\Http\Resources\CompetenciaResource;
use App\Services\Competencia\CompetenciaService;
use App\Services\Competencia\CierreCompetenciaService;
use App\Repositories\Competencia\CompetenciaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Events\Competencia\CompetenciaCreada;
use App\Repositories\Area\AreaRepository;
use App\Repositories\Fase\FaseGlobalRepository;

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
            'mensaje' => 'Competencia creada exitosamente.',
            'datos'   => new CompetenciaResource($competencia),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'datos' => new CompetenciaResource($this->repository->findWithFullHierarchy($id)),
        ]);
    }

    public function update(UpdateCompetenciaRequest $request, int $id): JsonResponse
    {
        $competencia = $this->service->actualizar($id, $request->validated());

        return response()->json([
            'mensaje' => 'Competencia actualizada correctamente.',
            'datos'   => new CompetenciaResource($competencia),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->eliminar($id);

        return response()->json(['mensaje' => 'Competencia eliminada correctamente.']);
    }

    public function publicar(int $id): JsonResponse
    {
        $competencia = $this->service->publicar($id);

        return response()->json([
            'mensaje' => 'Competencia publicada. Ahora es visible.',
            'datos'   => new CompetenciaResource($competencia),
        ]);
    }

    public function iniciar(int $id): JsonResponse
    {
        $competencia = $this->service->iniciar($id);

        return response()->json([
            'mensaje' => 'Competencia iniciada (En Proceso).',
            'datos'   => new CompetenciaResource($competencia),
        ]);
    }

    public function cerrar(ConcluirCompetenciaRequest $request, int $id): JsonResponse
    {
        $competencia = $this->cierreService->concluirYCalcular($id);

        return response()->json([
            'mensaje' => 'Competencia concluida. Resultados calculados y medallas asignadas.',
            'datos'   => new CompetenciaResource($competencia),
        ]);
    }

    public function avalar(AvalarCompetenciaRequest $request, int $id): JsonResponse
    {
        $competencia = $this->cierreService->avalar($id, auth()->id());

        return response()->json([
            'mensaje' => 'Resultados avalados oficialmente.',
            'datos'   => new CompetenciaResource($competencia),
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
