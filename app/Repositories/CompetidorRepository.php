<?php

namespace App\Repositories;

use App\Models\Competidor;
use App\Models\Persona;
use App\Models\Institucion;
use App\Models\Departamento;
use App\Models\GradoEscolaridad;
use App\Models\Area;
use App\Models\Nivel;
use App\Models\DescalificacionAdministrativa;
use App\Models\AreaOlimpiada;
use App\Models\AreaNivel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CompetidorRepository
{
    public function createPersona(array $data): Persona
    {
        return Persona::create($data);
    }

    public function createCompetidor(array $data): Competidor
    {
        return Competidor::create($data);
    }

    public function getPersonasConCompetidores(array $cis): Collection
    {
        return Persona::whereIn('ci', $cis)

            ->with(['competidores.archivoCsv', 'competidores.areaNivel'])
            ->get();
    }

    public function getAllInstituciones(): Collection
    {
        return Cache::remember('catalogo_instituciones', 86400, fn() => Institucion::all());
    }

    public function getInstitucionesByNombres(array $nombres): Collection
    {
        return $this->getAllInstituciones()->whereIn('nombre', $nombres);
    }

    public function getAllDepartamentos(): Collection {
        return Cache::remember('catalogo_departamentos', 86400, fn() => Departamento::all());
    }

    public function getAllGrados(): Collection {
        return Cache::remember('catalogo_grados', 86400, fn() => GradoEscolaridad::all());
    }

    public function getAllAreas(): Collection {
        return Cache::remember('catalogo_areas', 86400, fn() => Area::all());
    }

    public function getAllNiveles(): Collection {
        return Cache::remember('catalogo_niveles', 86400, fn() => Nivel::all());
    }

    public function getAreaOlimpiadas(int $olimpiadaId): Collection
    {
        return AreaOlimpiada::where('id_olimpiada', $olimpiadaId)->get();
    }

    public function getAreaNiveles(array $areaOlimpiadaIds): Collection
    {
        return AreaNivel::whereIn('id_area_olimpiada', $areaOlimpiadaIds)
            ->with(['areaOlimpiada', 'nivel'])
            ->get();
    }

    public function registrarDescalificacionAdministrativa(int $id_competidor, string $observaciones): void
    {
        DescalificacionAdministrativa::create([
            'id_competidor' => $id_competidor,
            'observaciones' => $observaciones,
        ]);
    }

    /**
     * Obtiene competidores para generar evaluaciones, aplicando filtros estrictos.
     * Filtro 1: Estar habilitado (campo en tabla competidor).
     * Filtro 2: Pertenecer a una Olimpiada con estado Activo (1).
     * @return \Illuminate\Database\Eloquent\Collection<int, Competidor>
     */
    public function getHabilitadosPorAreaNivel(int $idAreaNivel): Collection
    {
        return Competidor::query()
            ->where('id_area_nivel', $idAreaNivel)
            ->whereHas('areaNivel.areaOlimpiada.olimpiada', function ($query) {
                $query->where('estado', 1);
            })
            ->get();
    }
}
