<?php

namespace App\Http\Controllers;

use App\Http\Requests\Examen\StoreExamenRequest;
use App\Http\Requests\Examen\UpdateExamenRequest;
use App\Services\ExamenService;
use App\Model\Examen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Events\ExamenCreado;

class ExamenController extends Controller
{
    public function __construct(
        protected ExamenService $service
    ) {}

    /**
     * Listar exámenes de una competencia (paginado).
     *
     * Query param: ?por_pagina=15 (predeterminado)
     */
    public function index(Request $request, int $competenciaId): JsonResponse
    {
        $porPagina = $request->integer('por_pagina', 15);

        $examenes = Examen::where('id_competencia', $competenciaId)
            ->orderBy('created_at', 'desc')
            ->paginate($porPagina);

        return response()->json($examenes);
    }

    public function store(StoreExamenRequest $request): JsonResponse
    {
        $examen = $this->service->crearExamen($request->validated());
        broadcast(new ExamenCreado($examen))->toOthers();

        return response()->json(['message' => 'Examen creado.', 'data' => $examen], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Examen::with('competencia')->findOrFail($id));
    }

    public function update(UpdateExamenRequest $request, int $id): JsonResponse
    {
        $examen = $this->service->actualizarExamen($id, $request->validated());
        broadcast(new ExamenCreado($examen))->toOthers();

        return response()->json(['message' => 'Actualizado.', 'data' => $examen]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->eliminarExamen($id);

        return response()->json(['message' => 'Eliminado.']);
    }

    public function iniciar(int $id): JsonResponse
    {
        $examen = $this->service->iniciarExamen($id);

        return response()->json(['message' => 'Mesa abierta.', 'data' => $examen]);
    }

    public function finalizar(int $id): JsonResponse
    {
        $examen = $this->service->finalizarExamen($id);

        return response()->json(['message' => 'Examen cerrado.', 'data' => $examen]);
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
