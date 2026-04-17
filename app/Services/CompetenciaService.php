<?php

namespace App\Services;

use App\Exceptions\Dominio\AutorizacionException;
use App\Exceptions\Dominio\CompetenciaException;
use App\Repositories\CompetenciaRepository;
use App\Models\Competencia;
use App\Repositories\UsuarioRepository;
use App\Events\CompetenciaEstadoCambiado;

class CompetenciaService
{
    public function __construct(
        protected CompetenciaRepository $repository,
        protected UsuarioRepository $usuarioRepo
    ) {}

    /**
     * Crea una competencia en estado 'borrador'.
     */
    public function crear(array $data): Competencia
    {
        return $this->repository->create($data);
    }

    /**
     * Actualiza la configuración. Solo si está en 'borrador'.
     */
    public function actualizar(int $id, array $data): Competencia
    {
        $competencia = $this->repository->find($id);

        if ($competencia->estado_fase !== 'borrador') {
            throw new CompetenciaException(
                "Solo se pueden editar competencias en estado borrador. Estado actual: {$competencia->estado_fase}."
            );
        }

        $this->repository->update($data, $id);
        return $this->repository->find($id);
    }

    /**
     * Elimina la competencia. Solo si está en 'borrador'.
     */
    public function eliminar(int $id): void
    {
        $competencia = $this->repository->find($id);

        if ($competencia->estado_fase !== 'borrador') {
            throw new CompetenciaException('No se puede eliminar una competencia activa o finalizada.');
        }

        $this->repository->delete($id);
    }

    /**
     * Paso 1 del ciclo de vida: Publicar (hacer visible en agenda).
     * Valida integridad matemática de ponderaciones antes de exponer.
     */
    public function publicar(int $id): Competencia
    {
        $competencia = $this->repository->find($id);

        if ($competencia->estado_fase !== 'borrador') {
            throw new CompetenciaException('La competencia ya no está en borrador y no puede publicarse nuevamente.');
        }

        if ($competencia->examenes()->count() === 0) {
            throw new CompetenciaException('No puedes publicar una competencia sin exámenes configurados.');
        }

        if ($competencia->criterio_clasificacion === 'suma_ponderada') {
            $suma = $competencia->examenes()->sum('ponderacion');
            if (round($suma, 2) != 100.00) {
                throw new CompetenciaException(
                    "La suma de ponderaciones de los exámenes debe ser exactamente 100%. Suma actual: {$suma}%."
                );
            }
        }

        $competencia->update(['estado_fase' => 'publicada']);
        broadcast(new CompetenciaEstadoCambiado($competencia, 'publicada'))->toOthers();

        return $competencia;
    }

    /**
     * Paso 2 del ciclo de vida: Iniciar (fase operativa).
     * Habilita a los responsables para abrir las mesas de examen.
     */
    public function iniciar(int $id): Competencia
    {
        $competencia = $this->repository->find($id);

        if ($competencia->estado_fase !== 'publicada') {
            throw new CompetenciaException(
                "La competencia debe estar en estado 'publicada' para poder iniciarse. Estado actual: {$competencia->estado_fase}."
            );
        }

        $competencia->update(['estado_fase' => 'en_proceso']);
        broadcast(new CompetenciaEstadoCambiado($competencia, 'en_proceso'))->toOthers();

        return $competencia;
    }

    public function listarPorResponsableYArea(int $idUsuario, int $idArea)
    {
        $esResponsable = $this->usuarioRepo->tieneRol($idUsuario, 'Responsable de Area');

        if (!$esResponsable) {
            throw new AutorizacionException("El usuario no tiene el rol de 'Responsable de Área'.");
        }

        return $this->repository->getByResponsableAndArea($idUsuario, $idArea);
    }

    public function listarNivelesPorArea(int $idArea)
    {
        return $this->repository->getNivelesPorAreaActual($idArea);
    }

    public function agruparAreasNivelesPorResponsable(int $idResponsable): array
    {
        $competencias = $this->repository->getActivasPorResponsable($idResponsable);

        if ($competencias->isEmpty()) {
            return [];
        }

        $agrupado  = $competencias->groupBy(fn ($comp) => $comp->areaNivel->areaOlimpiada->area->id_area);
        $resultado = [];

        foreach ($agrupado as $idArea => $items) {
            $primero = $items->first();

            $niveles = $items->map(fn ($comp) => [
                'id_area_nivel'  => $comp->id_area_nivel,
                'id_nivel'       => $comp->areaNivel->nivel->id_nivel,
                'nombre_nivel'   => $comp->areaNivel->nivel->nombre,
                'id_competencia' => $comp->id_competencia,
            ])->unique('id_area_nivel')->values();

            $resultado[] = [
                'id_area' => $idArea,
                'area'    => $primero->areaNivel->areaOlimpiada->area->nombre,
                'niveles' => $niveles,
            ];
        }

        return $resultado;
    }
}
