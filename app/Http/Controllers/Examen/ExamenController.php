<?php

namespace App\Http\Controllers\Examen;

use App\Http\Requests\Examen\StoreExamenRequest;
use App\Http\Requests\Examen\UpdateExamenRequest;
use App\Http\Resources\ExamenResource;
use App\Services\Examen\ExamenService;
use App\Repositories\Examen\ExamenRepository;
use App\Models\Examen;
use App\Events\Examen\ExamenCreado;
use App\Events\Examen\ExamenEstadoCambiado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExamenController extends Controller
{
    public function __construct(
        protected ExamenService $service,
        protected ExamenRepository $repository
    ) {}

    /**
     * Listar exámenes de una competencia (paginado).
     *
     * Query param: ?por_pagina=15 (predeterminado)
     */
    public function index(Request $request, int $competenciaId): JsonResponse
    {
        $porPagina = $request->integer('por_pagina', 15);

        $examenes = $this->repository->paginadosPorCompetencia($competenciaId, $porPagina);

        return response()->json($examenes);
    }

    public function store(StoreExamenRequest $request): JsonResponse
    {
        $examen = $this->service->crearExamen($request->validated());
        broadcast(new ExamenCreado($examen))->toOthers();

        return response()->json(['mensaje' => 'Examen creado.', 'datos' => new ExamenResource($examen)], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'datos' => new ExamenResource(Examen::with('competencia')->findOrFail($id)),
        ]);
    }

    public function update(UpdateExamenRequest $request, int $id): JsonResponse
    {
        $examen = $this->service->actualizarExamen($id, $request->validated());
        broadcast(new ExamenEstadoCambiado($examen, $examen->estado_ejecucion))->toOthers();

        return response()->json(['mensaje' => 'Examen actualizado.', 'datos' => new ExamenResource($examen)]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->eliminarExamen($id);

        return response()->json(['mensaje' => 'Examen eliminado.']);
    }

    public function iniciar(int $id): JsonResponse
    {
        $examen = $this->service->iniciarExamen($id);

        return response()->json(['mensaje' => 'Mesa abierta.', 'datos' => new ExamenResource($examen)]);
    }

    public function finalizar(int $id): JsonResponse
    {
        $examen = $this->service->finalizarExamen($id);

        return response()->json(['mensaje' => 'Examen cerrado.', 'datos' => new ExamenResource($examen)]);
    }

    public function indexPorAreaNivel(int $idAreaNivel): JsonResponse
    {
        return response()->json($this->service->listarPorAreaNivel($idAreaNivel));
    }

    public function comboPorAreaNivel(int $idAreaNivel): JsonResponse
    {
        return response()->json($this->service->listarParaCombo($idAreaNivel));
    }

    /**
     * Lista de competidores para la "Sala de Evaluación" o Pizarra.
     */
    public function competidoresPorExamen(int $id): JsonResponse
    {
        return response()->json($this->service->listarCompetidores($id));
    }
}
