<?php

namespace App\Repositories\Interfaces;

use App\Models\Usuario;

interface UsuarioRepositoryInterface
{
    public function findByEmail(string $email): ?Usuario;
    public function findByCiWithDetails(string $ci): ?Usuario;
    public function tieneRol(int $userId, string $nombreRol): bool;
}
