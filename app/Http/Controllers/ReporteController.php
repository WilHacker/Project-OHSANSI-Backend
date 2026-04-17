<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\Reporte\GetHistorialRequest;
use App\Services\ReporteService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReporteController extends Controller
{
    public function __construct(
        protected ReporteService $reporteService
    ) {}

    public function historialCalificaciones(GetHistorialRequest $request): JsonResponse
    {
        return response()->json(
            $this->reporteService->generarHistorialPaginado($request->validated())
        );
    }

    public function historialCambios(int $idEvaluacion): JsonResponse
    {
        return response()->json($this->reporteService->obtenerHistorialEvaluacion($idEvaluacion));
    }

    public function getAreas(): JsonResponse
    {
        return response()->json(['data' => $this->reporteService->obtenerAreasFiltro()]);
    }

    public function getNivelesPorArea(int $idArea): JsonResponse
    {
        return response()->json(['data' => $this->reporteService->obtenerNivelesFiltro($idArea)]);
    }

    public function ranking(int $idCompetencia): JsonResponse
    {
        return response()->json($this->reporteService->obtenerResultadosOficiales($idCompetencia));
    }

    public function exportarCertificados(int $idCompetencia): BinaryFileResponse
    {
        return $this->reporteService->exportarCertificados($idCompetencia);
    }

    public function exportarCeremonia(int $idCompetencia): BinaryFileResponse
    {
        return $this->reporteService->exportarCeremonia($idCompetencia);
    }

    public function exportarPublicacion(int $idCompetencia): BinaryFileResponse
    {
        return $this->reporteService->exportarPublicacion($idCompetencia);
    }

    public function exportarClasificados(int $idCompetencia): BinaryFileResponse
    {
        return $this->reporteService->exportarClasificados($idCompetencia);
    }
}
