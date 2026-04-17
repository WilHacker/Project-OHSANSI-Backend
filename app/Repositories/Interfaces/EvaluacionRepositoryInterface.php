<?php

namespace App\Repositories\Interfaces;

use App\Models\Evaluacion;
use Illuminate\Database\Eloquent\Collection;

interface EvaluacionRepositoryInterface
{
    public function find(int $id): Evaluacion;
    public function findForUpdate(int $id): Evaluacion;
    public function bloquear(Evaluacion $evaluacion, int $userId): Evaluacion;
    public function desbloquear(Evaluacion $evaluacion): Evaluacion;
    public function updateNota(Evaluacion $evaluacion, array $datos): Evaluacion;
    public function descalificar(Evaluacion $evaluacion, string $motivo): Evaluacion;
    public function registrarLog(array $datos): void;
    public function getAreasConExamenesPorEvaluador(int $userId): Collection;
}
