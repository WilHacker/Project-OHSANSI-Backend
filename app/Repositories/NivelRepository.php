<?php

namespace App\Repositories;

use App\Models\Nivel;
use Illuminate\Database\Eloquent\Collection;

class NivelRepository
{
    public function getAllNivel(): Collection
    {
        return Nivel::orderBy('nombre')->get();
    }

    public function createNivel(array $data): Nivel
    {
        return Nivel::create($data);
    }

    public function findById(int $id): ?Nivel
    {
        return Nivel::find($id);
    }
}
