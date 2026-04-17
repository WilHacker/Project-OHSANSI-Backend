<?php

namespace App\Repositories\Interfaces;

use App\Models\Competencia;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CompetenciaRepositoryInterface
{
    public function find(int $id): Competencia;
    public function findWithFullHierarchy(int $id): ?Competencia;
    public function create(array $data): Competencia;
    public function update(array $data, int $id): bool;
    public function delete(int $id): bool;
    public function getAll(int $porPagina = 15): LengthAwarePaginator;
    public function getByResponsableAndArea(int $idResponsable, int $idArea);
    public function getNivelesPorAreaActual(int $idArea);
    public function getActivasPorResponsable(int $idUsuario);
}
