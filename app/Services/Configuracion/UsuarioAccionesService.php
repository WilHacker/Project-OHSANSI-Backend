<?php

namespace App\Services\Configuracion;

use App\Models\Olimpiada;
use App\Models\Usuario;
use App\Repositories\Configuracion\PermisoRepository;

class UsuarioAccionesService
{
    public function __construct(
        private readonly PermisoRepository $permisoRepo
    ) {}

    // ─── Verificación rápida de permisos ──────────────────────────────────────

    public function esSuperAdmin(int $userId): bool
    {
        return $this->permisoRepo->esSuperAdmin($userId);
    }

    public function can(int $userId, string $accionCodigo): bool
    {
        return $this->permisoRepo->tieneAccion($userId, $accionCodigo);
    }

    // ─── Detalle completo de acciones disponibles ─────────────────────────────

    public function misAcciones(int $userId): array
    {
        return $this->obtenerDetalleCapacidades($userId);
    }

    public function obtenerDetalleCapacidades(int $userId): array
    {
        $usuario = Usuario::with(['roles', 'persona'])->find($userId);
        if (!$usuario) return ['error' => 'Usuario no encontrado'];

        $rolesIds = $usuario->roles->pluck('id_rol')->toArray();
        if (empty($rolesIds)) return ['error' => 'Usuario sin roles asignados'];

        $olimpiada = Olimpiada::where('estado', 1)->first();
        if (!$olimpiada) {
            return $this->respuestaVacia($usuario, 'No hay ninguna Olimpiada con estado = 1 (Activa).');
        }

        $cronograma = $this->permisoRepo->cronogramaFaseActiva($olimpiada->id_olimpiada);

        if (!$cronograma) {
            return $this->respuestaVacia($usuario, 'Olimpiada activa encontrada, pero NO hay ninguna fase en el cronograma con estado=1 y fechas vigentes hoy.');
        }

        $acciones = $this->permisoRepo->accionesPermitidasPorRoles($rolesIds, $cronograma->id_fase_global);

        return [
            'user_id' => $usuario->id_usuario,
            'usuario' => $usuario->persona?->nombre . ' ' . $usuario->persona?->apellido,
            'roles'   => $usuario->roles->pluck('nombre'),
            'debug_estado' => [
                'olimpiada_activa'  => $olimpiada->nombre,
                'fase_detectada'    => $cronograma->nombre_fase,
                'cronograma_activo' => true,
            ],
            'acciones_permitidas' => $acciones,
        ];
    }

    private function respuestaVacia(Usuario $usuario, string $motivo): array
    {
        return [
            'user_id' => $usuario->id_usuario,
            'usuario' => $usuario->persona?->nombre . ' ' . $usuario->persona?->apellido,
            'roles'   => $usuario->roles->pluck('nombre'),
            'debug_estado' => [
                'mensaje_sistema'   => $motivo,
                'cronograma_activo' => false,
            ],
            'acciones_permitidas' => [],
        ];
    }
}
