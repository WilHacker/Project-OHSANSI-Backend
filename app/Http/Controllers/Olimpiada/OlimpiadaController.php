<?php

namespace App\Http\Controllers\Olimpiada;

use App\Http\Requests\Olimpiada\StoreOlimpiadaAdminRequest;
use App\Http\Requests\Olimpiada\StoreOlimpiadaRequest;
use App\Http\Resources\OlimpiadaResource;
use App\Services\Olimpiada\OlimpiadaService;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class OlimpiadaController extends Controller
{
    public function __construct(
        protected OlimpiadaService $olimpiadaService
    ) {}

    public function olimpiadaActual(): JsonResponse
    {
        return response()->json([
            'mensaje' => 'Olimpiada actual obtenida correctamente.',
            'datos'   => new OlimpiadaResource($this->olimpiadaService->obtenerOlimpiadaActual()),
        ]);
    }

    public function olimpiadasAnteriores(): JsonResponse
    {
        return response()->json([
            'mensaje' => 'Olimpiadas anteriores obtenidas correctamente.',
            'datos'   => OlimpiadaResource::collection($this->olimpiadaService->obtenerOlimpiadasAnteriores()),
        ]);
    }

    public function gestiones(): JsonResponse
    {
        return response()->json([
            'mensaje' => 'Gestiones obtenidas correctamente.',
            'datos'   => OlimpiadaResource::collection($this->olimpiadaService->obtenerGestiones()),
        ]);
    }

    public function index(): JsonResponse
    {
        $olimpiadas = $this->olimpiadaService->obtenerTodasOlimpiadas();

        return response()->json([
            'mensaje' => 'Todas las olimpiadas obtenidas correctamente.',
            'datos'   => OlimpiadaResource::collection($olimpiadas),
        ]);
    }

    public function store(StoreOlimpiadaRequest $request): JsonResponse
    {
        $olimpiada = $this->olimpiadaService->crearOlimpiada($request->validated());

        return response()->json([
            'mensaje' => 'Olimpiada creada correctamente.',
            'datos'   => new OlimpiadaResource($olimpiada),
        ], 201);
    }

    public function activar(int $id): JsonResponse
    {
        $olimpiada = $this->olimpiadaService->obtenerOlimpiadaPorId($id);

        if (!$olimpiada) {
            return response()->json(['mensaje' => 'Olimpiada no encontrada.'], 404);
        }

        $this->olimpiadaService->activarOlimpiada($id);

        return response()->json([
            'mensaje' => 'Olimpiada activada exitosamente.',
            'datos'   => new OlimpiadaResource($this->olimpiadaService->obtenerOlimpiadaPorId($id)),
        ]);
    }

    public function storeAdmin(StoreOlimpiadaAdminRequest $request): JsonResponse
    {
        $olimpiada = $this->olimpiadaService->crearOlimpiadaDirecta($request->validated());

        return response()->json([
            'mensaje' => $olimpiada->estado
                ? 'Olimpiada creada y activada (las anteriores fueron cerradas).'
                : 'Olimpiada creada correctamente como inactiva.',
            'datos'   => new OlimpiadaResource($olimpiada),
        ], 201);
    }
}
