<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Services\ConfiguracionAccionService;
use App\Services\UserActionService;
use App\Http\Requests\ConfiguracionAccion\UpdateConfiguracionAccionRequest;
use App\Exceptions\Dominio\AutorizacionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfiguracionAccionController extends Controller
{
    public function __construct(
        protected ConfiguracionAccionService $service,
        protected UserActionService $gate
    ) {}

    public function index(Request $request): JsonResponse
    {
        if (!$this->gate->esSuperAdmin(auth()->id())) {
            throw new AutorizacionException('Acceso denegado. Solo el Administrador puede ver la configuración.');
        }

        $matriz = $this->service->obtenerMatrizCompleta();

        return response()->json([
            'mensaje' => 'Matriz de configuración obtenida.',
            'datos'   => $matriz,
        ]);
    }

    public function update(UpdateConfiguracionAccionRequest $request): JsonResponse
    {
        if (!$this->gate->esSuperAdmin(auth()->id())) {
            throw new AutorizacionException('Acceso denegado. Solo el Administrador puede cambiar la configuración.');
        }

        $this->service->update($request->validated());

        return response()->json([
            'mensaje' => 'Configuración actualizada correctamente.',
        ]);
    }
}
