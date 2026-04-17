<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\AreaNivelService;
use Illuminate\Routing\Controller;
use App\Models\AreaOlimpiada;
use App\Models\AreaNivel;

class AreaNivelController extends Controller
{
    public function __construct(
        protected AreaNivelService $areaNivelService
    ) {}

    public function show(int $id): JsonResponse
    {
        $result = $this->areaNivelService->getAreaNivelById($id);

        if (!$result) {
            return response()->json(['success' => false, 'message' => 'Relación área-nivel no encontrada'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $result['area_nivel'],
            'message' => $result['message'],
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        // No se puede eliminar si ya existe al menos una fase global configurada
        if (\App\Models\FaseGlobal::exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Se está en una fase de evaluación, por lo tanto no se pueden modificar los datos',
            ], 422);
        }

        $result = $this->areaNivelService->deleteAreaNivel($id);

        return response()->json(['success' => true, 'message' => $result['message']]);
    }

    public function getActuales(): JsonResponse
    {
        return response()->json($this->areaNivelService->getAreasNivelesGestionActual());
    }

    public function getAllWithDetails(): JsonResponse
    {
        $result = $this->areaNivelService->getAllAreaNivelWithDetails();

        return response()->json([
            'success' => true,
            'data'    => $result['area_niveles'],
            'message' => $result['message'],
        ]);
    }

    public function getByArea(int $id_area): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->areaNivelService->getAreaNivelByArea($id_area),
        ]);
    }

    public function getAreasConNivelesPorOlimpiada(int $id_olimpiada): JsonResponse
    {
        $result = $this->areaNivelService->getAreasConNivelesPorOlimpiada($id_olimpiada);

        return response()->json([
            'success'  => true,
            'data'     => $result['areas'],
            'olimpiada' => $result['olimpiada'],
            'message'  => $result['message'],
        ]);
    }

    public function getAreasConNivelesPorGestion(string $gestion): JsonResponse
    {
        $result = $this->areaNivelService->getAreasConNivelesPorGestion($gestion);

        return response()->json([
            'success'  => true,
            'data'     => $result['areas'],
            'olimpiada' => $result['olimpiada'],
            'message'  => $result['message'],
        ]);
    }

    public function getNivelesPorAreaOlimpiada(int $idOlimpiada, int $idArea): JsonResponse
    {
        $areaOlimpiada = AreaOlimpiada::where('id_olimpiada', $idOlimpiada)
            ->where('id_area', $idArea)
            ->first();

        if (!$areaOlimpiada) {
            return response()->json(['success' => true, 'message' => 'No hay niveles', 'data' => []]);
        }

        $niveles = AreaNivel::with('nivel')
            ->where('id_area_olimpiada', $areaOlimpiada->id_area_olimpiada)
            ->get()
            ->map(fn ($an) => [
                'id_nivel' => $an->nivel->id_nivel,
                'nombre'   => $an->nivel->nombre,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Niveles del área obtenidos correctamente',
            'data'    => $niveles,
        ]);
    }

    public function update(int $id): JsonResponse
    {
        // Implementar cuando se requiera
        return response()->json(['message' => 'Método no implementado'], 501);
    }

    public function updateByArea(int $id_area): JsonResponse
    {
        // Implementar cuando se requiera
        return response()->json(['message' => 'Método no implementado'], 501);
    }
}
