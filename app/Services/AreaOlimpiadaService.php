<?php

namespace App\Services;

use App\Repositories\AreaOlimpiadaRepository;
use App\Models\Olimpiada;
use Illuminate\Support\Collection;

class AreaOlimpiadaService
{
    public function __construct(
        protected AreaOlimpiadaRepository $areaOlimpiadaRepository
    ) {}

    public function getAreasByOlimpiada(int|string $identifier): Collection
    {
        if (is_numeric($identifier) && strlen((string) $identifier) === 4) {
            return $this->areaOlimpiadaRepository->findAreasByGestion((string) $identifier);
        }

        return $this->areaOlimpiadaRepository->findAreasByOlimpiadaId((int) $identifier);
    }

    public function getAreasGestionActual(): Collection
    {
        $olimpiada = Olimpiada::where('estado', true)->first();

        return $olimpiada
            ? $this->areaOlimpiadaRepository->findAreasByGestion($olimpiada->gestion)
            : collect();
    }

    public function getNombresAreasGestionActual(): Collection
    {
        return $this->getAreasGestionActual()->pluck('nombre', 'id_area');
    }

    public function getAreasByGestion(string $gestion): Collection
    {
        return $this->areaOlimpiadaRepository->findAreasByGestion($gestion);
    }

    public function getAreasByOlimpiadaAndResponsable(int $idOlimpiada, int $idResponsable): Collection
    {
        return $this->areaOlimpiadaRepository->findAreasByOlimpiadaAndResponsable($idOlimpiada, $idResponsable);
    }
}
