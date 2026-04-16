<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\Departamento\StoreDepartamentoRequest;
use App\Services\DepartamentoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function __construct(
        protected DepartamentoService $departamentoService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json($this->departamentoService->listarDepartamentos());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->departamentoService->obtenerDepartamento($id));
    }

    public function store(StoreDepartamentoRequest $request): JsonResponse
    {
        $departamento = $this->departamentoService->crearDepartamento($request->validated());

        return response()->json([
            'message' => 'Departamento creado exitosamente',
            'data'    => $departamento,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'nombre' => 'sometimes|string|max:20|unique:departamento,nombre,' . $id . ',id_departamento',
        ]);

        $departamento = $this->departamentoService->actualizarDepartamento($id, $request->all());

        return response()->json([
            'message' => 'Departamento actualizado correctamente',
            'data'    => $departamento,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->departamentoService->eliminarDepartamento($id);

        return response()->json(['message' => 'Departamento eliminado correctamente']);
    }
}
