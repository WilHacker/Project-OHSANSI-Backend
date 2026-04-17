<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AreaService;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\AreaOlimpiada;
use App\Models\Olimpiada;

class AreaController extends Controller
{
    public function __construct(
        protected AreaService $areaService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->areaService->getAreaList());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:area,nombre',
        ]);

        $area = $this->areaService->createNewArea($validated);

        return response()->json(['area' => $area], 201);
    }

    public function getActualesPlanas(): JsonResponse
    {
        $olimpiada = Olimpiada::latest('id_olimpiada')->first();

        if (!$olimpiada) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $areas = AreaOlimpiada::with('area')
            ->where('id_olimpiada', $olimpiada->id_olimpiada)
            ->get()
            ->pluck('area')
            ->unique('id_area')
            ->map(fn ($area) => [
                'id_area' => $area->id_area,
                'nombre'  => $area->nombre,
            ])->values();

        return response()->json([
            'success' => true,
            'message' => 'Áreas obtenidas correctamente',
            'data'    => $areas,
        ]);
    }
}
