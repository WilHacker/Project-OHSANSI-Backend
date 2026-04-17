<?php

namespace App\Repositories;

use App\Models\Olimpiada;
use Illuminate\Database\Eloquent\Collection;

class OlimpiadaRepository
{
    public function getAll(): Collection
    {
        return Olimpiada::orderBy('gestion', 'desc')->get();
    }

    public function getAnteriores(string $gestionActual): Collection
    {
        return Olimpiada::where('gestion', '!=', $gestionActual)
            ->orderBy('gestion', 'desc')
            ->get();
    }

    public function find(int $id): ?Olimpiada
    {
        return Olimpiada::find($id);
    }

    public function findActive(): ?Olimpiada
    {
        return Olimpiada::where('estado', true)
            ->orderByDesc('id_olimpiada')
            ->first();
    }

    public function obtenerMasReciente(): ?Olimpiada
    {
        return Olimpiada::orderByDesc('gestion')
            ->orderByDesc('id_olimpiada')
            ->first();
    }

    public function firstOrCreate(array $attributes, array $values = []): Olimpiada
    {
        return Olimpiada::firstOrCreate($attributes, $values);
    }

    public function create(array $data): Olimpiada
    {
        $data['estado'] = $data['estado'] ?? false;
        return Olimpiada::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) Olimpiada::where('id_olimpiada', $id)->update($data);
    }

    public function desactivarTodas(): void
    {
        Olimpiada::query()->update(['estado' => false]);
    }

    public function activar(int $id): bool
    {
        return (bool) Olimpiada::where('id_olimpiada', $id)->update(['estado' => true]);
    }
}
