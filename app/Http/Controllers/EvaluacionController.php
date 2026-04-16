<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\Evaluacion\BloquearFichaRequest;
use App\Http\Requests\Evaluacion\DesbloquearFichaRequest;
use App\Http\Requests\Evaluacion\GuardarNotaRequest;
use App\Http\Requests\Evaluacion\DescalificarCompetidorRequest;
use App\Services\EvaluacionService;
use Illuminate\Http\JsonResponse;

class EvaluacionController extends Controller
{
    public function __construct(
        protected EvaluacionService $service
    ) {}

    public function index(int $idExamen): JsonResponse
    {
        $data = $this->service->obtenerPizarraExamen($idExamen);
        return response()->json($data);
    }

    public function bloquear(BloquearFichaRequest $request, int $id): JsonResponse
    {
        $evaluacion = $this->service->bloquearFicha($id, auth()->id());
        return response()->json(['mensaje' => 'Ficha bloqueada.', 'datos' => $evaluacion]);
    }

    public function guardarNota(GuardarNotaRequest $request, int $id): JsonResponse
    {
        // Se inyecta el id del usuario autenticado para la lógica de bloqueo en el service
        $datos = array_merge($request->validated(), ['user_id' => auth()->id()]);
        $evaluacion = $this->service->guardarNota($id, $datos);
        return response()->json(['mensaje' => 'Calificación guardada.', 'datos' => $evaluacion]);
    }

    public function desbloquear(DesbloquearFichaRequest $request, int $id): JsonResponse
    {
        $this->service->desbloquearFicha($id, auth()->id());
        return response()->json(['mensaje' => 'Ficha liberada.']);
    }

    public function descalificar(DescalificarCompetidorRequest $request, int $id): JsonResponse
    {
        $evaluacion = $this->service->descalificarCompetidor(
            $id,
            auth()->id(),
            $request->input('motivo')
        );

        return response()->json([
            'mensaje' => 'Competidor descalificado correctamente.',
            'datos'   => $evaluacion,
        ]);
    }

    /**
     * Menú del juez: muestra en qué áreas/niveles puede evaluar.
     */
    public function listarAreasNiveles(int $idUsuario): JsonResponse
    {
        $data = $this->service->listarAreasNivelesParaEvaluador($idUsuario);
        return response()->json($data);
    }
}
