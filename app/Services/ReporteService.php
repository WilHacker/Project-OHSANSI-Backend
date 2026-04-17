<?php

namespace App\Services;

use App\Enums\EstadoCompetencia;
use App\Exceptions\Dominio\RecursoNoEncontradoException;
use App\Exceptions\Dominio\CompetenciaException;
use App\Exports\CertificadosExport;
use App\Exports\CeremoniaExport;
use App\Exports\PublicacionExport;
use App\Exports\ClasificadosExport;
use App\Models\Competencia;
use App\Repositories\ReporteRepository;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReporteService
{
    public function __construct(
        protected ReporteRepository $repo
    ) {}

    public function generarHistorialPaginado(array $params): array
    {
        $page  = (int) $params['page'];
        $limit = (int) $params['limit'];

        $filtros = [
            'id_area'     => $params['id_area'] ?? null,
            'ids_niveles' => isset($params['ids_niveles']) ? explode(',', $params['ids_niveles']) : [],
            'search'      => $params['search'] ?? null,
        ];

        return $this->repo->getHistorialCompleto($page, $limit, $filtros);
    }

    public function obtenerAreasFiltro(): mixed
    {
        return $this->repo->getAreasActivas();
    }

    public function obtenerNivelesFiltro(int $idArea): mixed
    {
        return $this->repo->getNivelesActivosPorArea($idArea);
    }

    public function obtenerResultadosOficiales(int $idCompetencia): array
    {
        $medallero = $this->repo->getMedallero($idCompetencia);

        if ($medallero->isNotEmpty()) {
            return [
                'tipo'   => 'MEDALLERO_FINAL',
                'titulo' => 'Medallero Oficial',
                'data'   => $medallero
            ];
        }

        $clasificados = $this->repo->getClasificados($idCompetencia);

        return [
            'tipo'   => 'LISTA_CLASIFICADOS',
            'titulo' => 'Nómina de Clasificados',
            'data'   => $clasificados
        ];
    }

    public function obtenerHistorialEvaluacion(int $idEvaluacion): array
    {
        $logs = $this->repo->getLogCambios($idEvaluacion);

        if ($logs->isEmpty()) {
            return [
                'mensaje' => 'No existen cambios registrados para esta evaluación.',
                'data'    => []
            ];
        }

        return [
            'mensaje' => 'Historial recuperado correctamente.',
            'data'    => $logs
        ];
    }

    public function exportarCertificados(int $idCompetencia): BinaryFileResponse
    {
        $competencia = $this->resolverCompetenciaAvalada($idCompetencia);

        $datos = $this->repo->getDatosExportMedallero($idCompetencia);

        if ($datos->isEmpty()) {
            throw new CompetenciaException('No hay ganadores registrados para esta competencia.');
        }

        return Excel::download(
            new CertificadosExport($datos),
            "certificados_competencia_{$competencia->id_competencia}.xlsx"
        );
    }

    public function exportarCeremonia(int $idCompetencia): BinaryFileResponse
    {
        $competencia = $this->resolverCompetenciaAvalada($idCompetencia);

        $datos = $this->repo->getDatosExportMedallero($idCompetencia);

        if ($datos->isEmpty()) {
            throw new CompetenciaException('No hay ganadores registrados para esta competencia.');
        }

        return Excel::download(
            new CeremoniaExport($datos),
            "ceremonia_competencia_{$competencia->id_competencia}.xlsx"
        );
    }

    public function exportarPublicacion(int $idCompetencia): BinaryFileResponse
    {
        $competencia = $this->resolverCompetenciaAvalada($idCompetencia);

        $datos = $this->repo->getDatosExportMedallero($idCompetencia);

        if ($datos->isEmpty()) {
            throw new CompetenciaException('No hay ganadores registrados para esta competencia.');
        }

        return Excel::download(
            new PublicacionExport($datos),
            "publicacion_competencia_{$competencia->id_competencia}.xlsx"
        );
    }

    public function exportarClasificados(int $idCompetencia): BinaryFileResponse
    {
        $competencia = Competencia::find($idCompetencia);

        if (!$competencia) {
            throw new RecursoNoEncontradoException('Competencia no encontrada.');
        }

        $estadosPermitidos = [
            EstadoCompetencia::Concluida->value,
            EstadoCompetencia::Avalada->value,
        ];

        if (!in_array($competencia->estado_fase, $estadosPermitidos)) {
            throw new CompetenciaException(
                'La lista de clasificados solo está disponible cuando la competencia está cerrada o avalada.'
            );
        }

        $datos = $this->repo->getDatosExportClasificados($idCompetencia);

        return Excel::download(
            new ClasificadosExport($datos),
            "clasificados_competencia_{$competencia->id_competencia}.xlsx"
        );
    }

    private function resolverCompetenciaAvalada(int $idCompetencia): Competencia
    {
        $competencia = Competencia::find($idCompetencia);

        if (!$competencia) {
            throw new RecursoNoEncontradoException('Competencia no encontrada.');
        }

        if ($competencia->estado_fase !== EstadoCompetencia::Avalada->value) {
            throw new CompetenciaException(
                'Este reporte solo está disponible cuando la competencia ha sido avalada por el responsable de área.'
            );
        }

        return $competencia;
    }
}
