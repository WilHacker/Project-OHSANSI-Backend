<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Olimpiada;
use App\Services\AreaNivelGradoService;
use Illuminate\Routing\Controller;

class AreaNivelGradoController extends Controller
{
    protected $areaNivelGradoService;

    public function __construct(AreaNivelGradoService $areaNivelGradoService)
    {
        $this->areaNivelGradoService = $areaNivelGradoService;
    }

    public function index(): JsonResponse
    {
        $result = $this->areaNivelGradoService->getAreasConNiveles();

        return response()->json([
            'success'          => true,
            'data'             => $result['areas'],
            'olimpiada_actual' => $result['olimpiada_actual'],
            'message'          => $result['message'],
        ]);
    }

    public function getAreasConNiveles(): JsonResponse
    {
        $result = $this->areaNivelGradoService->getAreasConNiveles();

        return response()->json([
            'success'          => true,
            'data'             => $result['areas'],
            'olimpiada_actual' => $result['olimpiada_actual'],
            'message'          => $result['message'],
        ]);
    }

    public function getAreasConNivelesSimplificado(): JsonResponse
    {
        $result = $this->areaNivelGradoService->getAreasConNivelesSimplificado();

        return response()->json([
            'success'          => true,
            'data'             => $result['areas'],
            'olimpiada_actual' => $result['olimpiada_actual'],
            'message'          => $result['message'],
        ]);
    }

    public function getNivelesGradosByAreaAndGestion(string $gestion, int $id_area): JsonResponse
    {
        $result = $this->areaNivelGradoService->getNivelesGradosByAreaAndGestion($id_area, $gestion);

        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function getNivelesGradosByAreaAndResponsable(int $id_responsable, int $id_area): JsonResponse
    {
        $olimpiadaActiva = Olimpiada::where('estado', true)->first();

        if (!$olimpiadaActiva) {
            return response()->json([
                'success' => false,
                'data'    => ['niveles_con_grados_agrupados' => [], 'niveles_individuales' => []],
                'message' => 'No se encontró la olimpiada activa.',
            ], 404);
        }

        if (!$this->areaNivelGradoService->verificarAccesoResponsable($olimpiadaActiva->id_olimpiada, $id_area, $id_responsable)) {
            return response()->json([
                'success' => false,
                'data'    => ['niveles_con_grados_agrupados' => [], 'niveles_individuales' => []],
                'message' => 'El responsable no tiene acceso a esta área.',
            ], 403);
        }

        $result = $this->areaNivelGradoService->getNivelesGradosByAreaAndGestion($id_area, $olimpiadaActiva->gestion);

        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function getNivelesGradosByAreasAndGestion(Request $request, string $gestion): JsonResponse
    {
        $validated = $request->validate([
            'id_areas'   => 'required|array',
            'id_areas.*' => 'integer|exists:area,id_area',
        ]);

        $result = $this->areaNivelGradoService->getNivelesGradosByAreasAndGestion($validated['id_areas'], $gestion);

        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function getByGestionAndAreas(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gestion'    => 'required|string',
            'id_areas'   => 'required|array',
            'id_areas.*' => 'integer|exists:area,id_area',
        ]);

        $result = $this->areaNivelGradoService->getByGestionAndAreas($validated['gestion'], $validated['id_areas']);

        return response()->json([
            'success'  => true,
            'data'     => $result['area_niveles'],
            'olimpiada' => $result['olimpiada'],
            'message'  => $result['message'],
        ]);
    }

    public function getByAreaAll(int $id_area): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->areaNivelGradoService->getAreaNivelByAreaAll($id_area),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            '*.id_area'               => 'required|integer|exists:area,id_area',
            '*.id_nivel'              => 'required|integer|exists:nivel,id_nivel',
            '*.id_grado_escolaridad'  => 'required|integer|exists:grado_escolaridad,id_grado_escolaridad',
            '*.activo'                => 'required|boolean',
        ]);

        $result = $this->areaNivelGradoService->createMultipleAreaNivelWithGrades($validatedData);

        $response = [
            'success'          => true,
            'data'             => $result['area_niveles'],
            'message'          => $result['message'],
            'olimpiada_actual' => $result['olimpiada'],
            'success_count'    => $result['success_count'],
            'created_count'    => count($result['area_niveles']),
        ];

        if (!empty($result['errors'])) {
            $response['errors']      = $result['errors'];
            $response['error_count'] = $result['error_count'];
        }

        if (!empty($result['distribucion'])) {
            $response['distribucion'] = $result['distribucion'];
        }

        return response()->json($response, 201);
    }
}
