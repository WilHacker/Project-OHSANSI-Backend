<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\StoreParametroRequest;
use App\Services\ParametroService;
use App\Model\Olimpiada;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParametroController extends Controller
{
    public function __construct(
        protected ParametroService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->service->getAllParametros()]);
    }

    public function store(StoreParametroRequest $request): JsonResponse
    {
        $parametrosGuardados = $this->service->guardarParametrosMasivos(
            $request->validated()['area_niveles']
        );

        $data = collect($parametrosGuardados)->map(fn ($p) => [
            'id_parametro'        => $p->id_parametro,
            'id_area_nivel'       => $p->id_area_nivel,
            'nota_min_aprobacion' => $p->nota_min_aprobacion,
            'cantidad_maxima'     => $p->cantidad_maxima,
            'area_nivel'          => [
                'area'  => $p->areaNivel->areaOlimpiada->area->nombre ?? null,
                'nivel' => $p->areaNivel->nivel->nombre ?? null,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Parámetros guardados exitosamente.',
            'data'    => $data,
        ], 201);
    }

    public function getByOlimpiada(int $idOlimpiada): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->service->getParametrosPorOlimpiada($idOlimpiada)]);
    }

    public function getParametrosByAreaNiveles(Request $request): JsonResponse
    {
        $request->validate(['ids' => 'nullable|string']);

        $idsInput = $request->input('ids', '');

        if (empty($idsInput)) {
            return response()->json(['success' => true, 'data' => [], 'message' => 'No se proporcionaron IDs de área-nivel']);
        }

        $ids = array_values(array_filter(
            array_map('intval', explode(',', $idsInput)),
            fn ($id) => $id > 0
        ));

        if (empty($ids)) {
            return response()->json(['success' => true, 'data' => [], 'message' => 'Los IDs proporcionados no son válidos']);
        }

        return response()->json(['success' => true, 'data' => $this->service->getParametrosByAreaNiveles($ids)]);
    }

    public function getAllParametrosByGestiones(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->service->getAllParametrosByGestiones()]);
    }

    public function getParametrosGestionActual(): JsonResponse
    {
        $olimpiada = Olimpiada::where('estado', true)->first();

        if (!$olimpiada) {
            return response()->json([
                'success' => false,
                'message' => 'No hay olimpiadas activas en este momento.',
                'data'    => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->service->getParametrosPorOlimpiada($olimpiada->id_olimpiada),
        ]);
    }
}
