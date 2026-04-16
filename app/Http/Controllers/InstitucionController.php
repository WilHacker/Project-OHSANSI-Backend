<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\Institucion\StoreInstitucionRequest;
use App\Services\InstitucionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstitucionController extends Controller
{
    public function __construct(
        protected InstitucionService $institucionService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->institucionService->getAll()]);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->institucionService->findById($id)]);
    }

    public function store(StoreInstitucionRequest $request): JsonResponse
    {
        $data = $this->institucionService->create($request->validated());

        return response()->json(['success' => true, 'message' => 'Institución creada', 'data' => $data], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'nombre' => 'sometimes|string|max:250|unique:institucion,nombre,' . $id . ',id_institucion',
        ]);

        $data = $this->institucionService->update($id, $request->all());

        return response()->json(['success' => true, 'message' => 'Institución actualizada', 'data' => $data]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->institucionService->delete($id);

        return response()->json(['success' => true, 'message' => 'Institución eliminada']);
    }
}
