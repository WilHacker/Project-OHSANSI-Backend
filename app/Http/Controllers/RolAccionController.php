<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Services\RolAccionService;
use App\Services\UserActionService;
use App\Http\Requests\RolAccion\UpdateGlobalRolAccionRequest;
use App\Exceptions\Dominio\AutorizacionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolAccionController extends Controller
{
    public function __construct(
        protected RolAccionService $service,
        protected UserActionService $gate
    ) {}

    public function index(Request $request): JsonResponse
    {
        if (!$this->gate->esSuperAdmin(auth()->id())) {
            throw new AutorizacionException('Acceso denegado. Solo el Administrador puede ver los permisos.');
        }

        $matrizGlobal = $this->service->obtenerMatrizGlobal();

        return response()->json([
            'mensaje' => 'Matriz global de permisos obtenida.',
            'datos'   => $matrizGlobal,
        ]);
    }

    public function updateGlobal(UpdateGlobalRolAccionRequest $request): JsonResponse
    {
        if (!$this->gate->esSuperAdmin(auth()->id())) {
            throw new AutorizacionException('Acceso denegado. Solo el Administrador puede gestionar roles.');
        }

        $this->service->updateGlobal($request->input('roles'));

        return response()->json([
            'mensaje' => 'Permisos de roles actualizados correctamente.',
        ]);
    }
}
