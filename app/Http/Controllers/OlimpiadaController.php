<?php

namespace App\Http\Controllers;

use App\Services\OlimpiadaService;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OlimpiadaController extends Controller
{
    public function __construct(
        protected OlimpiadaService $olimpiadaService
    ) {}

    public function olimpiadaActual(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->olimpiadaService->obtenerOlimpiadaActual(),
            'message' => 'Olimpiada actual obtenida correctamente',
        ]);
    }

    public function olimpiadasAnteriores(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->olimpiadaService->obtenerOlimpiadasAnteriores(),
            'message' => 'Olimpiadas anteriores obtenidas correctamente',
        ]);
    }

    public function gestiones(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->olimpiadaService->obtenerGestiones(),
            'message' => 'Gestiones obtenidas correctamente',
        ]);
    }

    public function index(): JsonResponse
    {
        $olimpiadas = $this->olimpiadaService->obtenerTodasOlimpiadas();

        return response()->json([
            'success' => true,
            'data'    => $olimpiadas,
            'total'   => $olimpiadas->count(),
            'message' => 'Todas las olimpiadas obtenidas correctamente',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre'  => 'required|string|max:255|unique:olimpiada,nombre',
            'gestion' => 'required|string|size:4',
        ], [
            'nombre.unique' => 'Ya existe una olimpiada con este nombre.',
        ]);

        $olimpiada = $this->olimpiadaService->crearOlimpiada([
            'nombre'  => $request->nombre,
            'gestion' => $request->gestion,
        ]);

        return response()->json([
            'success' => true,
            'data'    => $olimpiada,
            'message' => 'Olimpiada creada correctamente',
        ], 201);
    }

    public function activar(int $id): JsonResponse
    {
        $olimpiada = $this->olimpiadaService->obtenerOlimpiadaPorId($id);

        if (!$olimpiada) {
            return response()->json(['success' => false, 'message' => 'Olimpiada no encontrada'], 404);
        }

        $this->olimpiadaService->activarOlimpiada($id);

        return response()->json([
            'success' => true,
            'data'    => $this->olimpiadaService->obtenerOlimpiadaPorId($id),
            'message' => 'Se escogió la olimpiada exitosamente.',
        ]);
    }

    /**
     * POST /api/v1/olimpiadas/admin
     * Crea una olimpiada con control total (puede activarla de una vez).
     */
    public function storeAdmin(Request $request): JsonResponse
    {
        $request->validate([
            'nombre'  => 'required|string|max:255|unique:olimpiada,nombre',
            'gestion' => 'required|string|size:4|unique:olimpiada,gestion',
            'estado'  => 'boolean',
        ], [
            'nombre.unique'  => 'Ya existe una olimpiada con este nombre.',
            'gestion.unique' => 'Ya existe una olimpiada con esta gestión.',
        ]);

        $olimpiada = $this->olimpiadaService->crearOlimpiadaDirecta($request->validated());

        return response()->json([
            'success' => true,
            'data'    => $olimpiada,
            'message' => $olimpiada->estado
                ? 'Olimpiada creada y activada (las anteriores fueron cerradas).'
                : 'Olimpiada creada correctamente como inactiva.',
        ], 201);
    }
}
