<?php

use App\Models\AreaNivel;
use App\Models\Competencia;
use App\Models\EvaluadorAn;
use App\Models\Examen;
use App\Models\ResponsableArea;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Autorización de canales privados de Reverb.
|
| Reglas generales:
|   - examen.{id}      → solo evaluadores activos del área-nivel del examen
|   - competencia.{id} → evaluadores activos O responsable del área-nivel
|   - area-nivel.{id}  → evaluadores activos O responsable del área-nivel
|   - usuario.{userId} → solo el propio usuario (canal de notificaciones personales)
|   - sistema-global   → público (cualquier usuario logueado)
|--------------------------------------------------------------------------
*/

// Canal personal de Laravel (notificaciones internas del framework)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id_usuario === (int) $id;
});

// ─── CANAL EXAMEN ──────────────────────────────────────────────────────────
// Eventos: CompetidorBloqueado, CompetidorLiberado, ExamenEstadoCambiado
// Acceso: evaluadores activos asignados al área-nivel del examen
Broadcast::channel('examen.{id}', function ($user, $id) {
    $examen = Examen::with('competencia:id_competencia,id_area_nivel')->find($id);

    if (!$examen) {
        return false;
    }

    return EvaluadorAn::where('id_usuario', $user->id_usuario)
        ->where('id_area_nivel', $examen->competencia->id_area_nivel)
        ->where('estado', true)
        ->exists();
});

// ─── CANAL COMPETENCIA ─────────────────────────────────────────────────────
// Eventos: ExamenEstadoCambiado, CompetenciaFinalizada, CompetenciaEstadoCambiado
// Acceso: evaluadores activos O responsable del área-olimpiada de la competencia
Broadcast::channel('competencia.{id}', function ($user, $id) {
    $competencia = Competencia::with('areaNivel:id_area_nivel,id_area_olimpiada')->find($id);

    if (!$competencia) {
        return false;
    }

    $idAreaNivel     = $competencia->id_area_nivel;
    $idAreaOlimpiada = $competencia->areaNivel->id_area_olimpiada;

    return EvaluadorAn::where('id_usuario', $user->id_usuario)
            ->where('id_area_nivel', $idAreaNivel)
            ->where('estado', true)
            ->exists()
        || ResponsableArea::where('id_usuario', $user->id_usuario)
            ->where('id_area_olimpiada', $idAreaOlimpiada)
            ->exists();
});

// ─── CANAL ÁREA-NIVEL ──────────────────────────────────────────────────────
// Eventos: CompetenciaCreada
// Acceso: evaluadores activos O responsable del área-olimpiada del área-nivel
Broadcast::channel('area-nivel.{id}', function ($user, $id) {
    $areaNivel = AreaNivel::find($id);

    if (!$areaNivel) {
        return false;
    }

    return EvaluadorAn::where('id_usuario', $user->id_usuario)
            ->where('id_area_nivel', $id)
            ->where('estado', true)
            ->exists()
        || ResponsableArea::where('id_usuario', $user->id_usuario)
            ->where('id_area_olimpiada', $areaNivel->id_area_olimpiada)
            ->exists();
});

// ─── CANAL USUARIO PERSONAL ────────────────────────────────────────────────
// Eventos: MisAccionesActualizadas
// Acceso: solo el propio usuario
Broadcast::channel('usuario.{userId}', function ($user, $userId) {
    return (int) $user->id_usuario === (int) $userId;
});

// ─── CANAL PÚBLICO GLOBAL ──────────────────────────────────────────────────
// Eventos: SistemaEstadoActualizado
// Acceso: cualquier usuario autenticado
Broadcast::channel('sistema-global', function ($user) {
    return $user !== null;
});
