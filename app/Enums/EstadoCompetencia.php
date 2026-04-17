<?php

namespace App\Enums;

enum EstadoCompetencia: string
{
    case Borrador   = 'borrador';
    case Publicada  = 'publicada';
    case EnProceso  = 'en_proceso';
    case Concluida  = 'concluida';
    case Avalada    = 'avalada';

    public function label(): string
    {
        return match($this) {
            self::Borrador  => 'Borrador',
            self::Publicada => 'Publicada',
            self::EnProceso => 'En Proceso',
            self::Concluida => 'Concluida',
            self::Avalada   => 'Avalada',
        };
    }

    public function esEditable(): bool
    {
        return $this === self::Borrador;
    }

    public function esActiva(): bool
    {
        return $this === self::EnProceso;
    }
}
