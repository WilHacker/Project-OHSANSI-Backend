<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Requests\Catalogo\StoreNivelRequest;
use App\Services\Catalogo\NivelService;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class NivelController extends Controller {
    protected $nivelService;

    public function __construct(NivelService $nivelService){
        $this->nivelService = $nivelService;
    }
    
    public function index() {
        $niveles = $this->nivelService->getNivelList();
        return response()->json($niveles);
    }

    public function store(StoreNivelRequest $request): JsonResponse
    {
        $nivel = $this->nivelService->createNewNivel($request->validated());

        return response()->json(['mensaje' => 'Nivel creado correctamente.', 'datos' => $nivel], 201);
    }

     public function show($id): JsonResponse
    {
        try {
            $nivel = $this->nivelService->findById($id);

            if (!$nivel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nivel no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $nivel
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el nivel: ' . $e->getMessage()
            ], 500);
        }
    }
}
