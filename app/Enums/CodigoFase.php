<?php

namespace App\Enums;

enum CodigoFase: string
{
    case Configuracion = 'CONFIGURACION';
    case Evaluacion    = 'EVALUACION';
    case Final         = 'FINAL';
}
