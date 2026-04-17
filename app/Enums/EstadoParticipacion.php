<?php

namespace App\Enums;

enum EstadoParticipacion: string
{
    case Presente      = 'presente';
    case Ausente       = 'ausente';
    case Descalificado = 'descalificado';
    case Normal        = 'normal';

    public function label(): string
    {
        return match($this) {
            self::Presente      => 'Presente',
            self::Ausente       => 'Ausente',
            self::Descalificado => 'Descalificado',
            self::Normal        => 'Normal',
        };
    }

    public function contaParaRanking(): bool
    {
        return $this === self::Presente || $this === self::Normal;
    }
}
