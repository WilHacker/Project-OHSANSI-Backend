<?php

namespace App\Repositories\Interfaces;

use App\Models\Examen;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ExamenRepositoryInterface
{
    public function find(int $id): Examen;
    public function create(array $data): Examen;
    public function update(array $data, int $id): bool;
    public function delete(int $id): bool;
    public function paginadosPorCompetencia(int $competenciaId, int $porPagina = 15): LengthAwarePaginator;
    public function getByAreaNivel(int $idAreaNivel): Collection;
    public function getSimpleByAreaNivel(int $idAreaNivel): Collection;
    public function getCompetidoresDeExamen(int $idExamen): Collection;
    public function sumarPonderaciones(int $competenciaId, ?int $excludeId = null): float;
}
