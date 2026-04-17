<?php

namespace App\Enums;

enum EstadoExamen: string
{
    case NoIniciada = 'no_iniciada';
    case EnCurso    = 'en_curso';
    case Finalizada = 'finalizada';

    public function label(): string
    {
        return match($this) {
            self::NoIniciada => 'No Iniciada',
            self::EnCurso    => 'En Curso',
            self::Finalizada => 'Finalizada',
        };
    }

    public function esEditable(): bool
    {
        return $this === self::NoIniciada;
    }
}
