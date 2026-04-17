<?php

namespace App\Http\Controllers;

use App\Services\AreaOlimpiadaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Models\Olimpiada;
use App\Models\FaseGlobal;

class AreaOlimpiadaController extends Controller
{
    public function __construct(
        protected AreaOlimpiadaService $areaOlimpiadaService
    ) {}

    public function getAreasByOlimpiada(int $identifier): JsonResponse
    {
        return response()->json([
            'message' => 'Áreas obtenidas exitosamente para la olimpiada.',
            'data'    => $this->areaOlimpiadaService->getAreasByOlimpiada($identifier),
        ]);
    }

    public function getAreasGestionActual(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->areaOlimpiadaService->getAreasGestionActual(),
        ]);
    }

    public function getNombresAreasGestionActual(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->areaOlimpiadaService->getNombresAreasGestionActual(),
        ]);
    }

    public function getAreasByGestion(string $gestion): JsonResponse
    {
        $areas    = $this->areaOlimpiadaService->getAreasByGestion($gestion);
        $olimpiada = Olimpiada::where('gestion', $gestion)->first();

        return response()->json([
            'success' => true,
            'message' => $olimpiada
                ? $this->mensajeFase($olimpiada->id_olimpiada)
                : 'No se encontró la olimpiada para la gestión proporcionada.',
            'data'    => $areas,
        ]);
    }

    public function getAreasByResponsableActiva(int $idResponsable): JsonResponse
    {
        $olimpiada = Olimpiada::where('estado', true)->first();

        if (!$olimpiada) {
            return response()->json([
                'success' => false,
                'message' => 'No hay ninguna olimpiada activa en este momento.',
            ], 404);
        }

        $areas = $this->areaOlimpiadaService->getAreasByOlimpiadaAndResponsable(
            $olimpiada->id_olimpiada,
            $idResponsable
        );

        return response()->json([
            'success'        => true,
            'message'        => $this->mensajeFase($olimpiada->id_olimpiada),
            'data'           => $areas,
            'total'          => $areas->count(),
            'olimpiada'      => [
                'id'      => $olimpiada->id_olimpiada,
                'nombre'  => $olimpiada->nombre,
                'gestion' => $olimpiada->gestion,
                'estado'  => $olimpiada->estado,
            ],
            'id_responsable' => $idResponsable,
        ]);
    }

    private function mensajeFase(int $idOlimpiada): string
    {
        $evaluacionActiva = FaseGlobal::where('id_olimpiada', $idOlimpiada)
            ->where(function ($q) {
                $q->where('nombre', 'like', '%valuaci%')
                  ->orWhere('nombre', 'like', '%alificaci%');
            })
            ->whereHas('cronograma', fn ($q) => $q->where('estado', true))
            ->exists();

        if ($evaluacionActiva) {
            return 'La funcionalidad de asignar niveles a un Área no está disponible porque el proceso de evaluación ha iniciado. Solo puede ver las asignaciones previamente realizadas.';
        }

        $hayFase = FaseGlobal::where('id_olimpiada', $idOlimpiada)
            ->whereHas('cronograma', fn ($q) => $q->where('estado', true))
            ->exists();

        return $hayFase
            ? 'No existe un proceso de evaluación activo.'
            : 'No existe un proceso de evaluación.';
    }
}
