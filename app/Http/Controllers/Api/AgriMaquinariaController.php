<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgriTipoMaquinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgriMaquinariaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AgriTipoMaquinaria::query();

            if ($request->has('categoria')) {
                $query->categoria($request->categoria);
            }

            if ($request->has('subcategoria')) {
                $query->subcategoria($request->subcategoria);
            }

            if ($request->has('activos') && $request->activos == 'true') {
                $query->activos();
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where('nombre', 'like', "%{$search}%");
            }

            $sortBy = $request->get('sort_by', 'nombre');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 20);
            $maquinaria = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $maquinaria->items(),
                'pagination' => [
                    'total' => $maquinaria->total(),
                    'per_page' => $maquinaria->perPage(),
                    'current_page' => $maquinaria->currentPage(),
                    'last_page' => $maquinaria->lastPage(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener maquinaria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:200',
                'categoria' => 'required|string|max:100',
                'subcategoria' => 'nullable|string|max:100',
                'especificaciones_tecnicas' => 'nullable|array',
                'unidad_medida' => 'required|string|max:50',
                'activo' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $maquinaria = AgriTipoMaquinaria::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Maquinaria creada exitosamente',
                'data' => $maquinaria
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear maquinaria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $maquinaria = AgriTipoMaquinaria::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $maquinaria
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Maquinaria no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $maquinaria = AgriTipoMaquinaria::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|string|max:200',
                'categoria' => 'sometimes|string|max:100',
                'subcategoria' => 'nullable|string|max:100',
                'especificaciones_tecnicas' => 'nullable|array',
                'unidad_medida' => 'sometimes|string|max:50',
                'activo' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $maquinaria->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Maquinaria actualizada exitosamente',
                'data' => $maquinaria
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar maquinaria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $maquinaria = AgriTipoMaquinaria::findOrFail($id);
            $maquinaria->delete();

            return response()->json([
                'success' => true,
                'message' => 'Maquinaria eliminada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar maquinaria',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
