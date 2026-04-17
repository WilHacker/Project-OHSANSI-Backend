<?php

namespace App\Enums;

enum TipoMedalla: string
{
    case Oro     = 'ORO';
    case Plata   = 'PLATA';
    case Bronce  = 'BRONCE';
    case Mencion = 'MENCION';

    public function label(): string
    {
        return match($this) {
            self::Oro     => 'Medalla de Oro',
            self::Plata   => 'Medalla de Plata',
            self::Bronce  => 'Medalla de Bronce',
            self::Mencion => 'Mención de Honor',
        };
    }
}
