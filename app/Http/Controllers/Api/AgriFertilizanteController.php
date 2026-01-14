<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgriFertilizante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgriFertilizanteController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AgriFertilizante::query();

            if ($request->has('tipo')) {
                $query->tipo($request->tipo);
            }

            if ($request->has('presentacion')) {
                $query->presentacion($request->presentacion);
            }

            if ($request->has('activos') && $request->activos == 'true') {
                $query->activos();
            }

            if ($request->has('search')) {
                $query->buscar($request->search);
            }

            $sortBy = $request->get('sort_by', 'nombre_comercial');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 20);
            $fertilizantes = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $fertilizantes->items(),
                'pagination' => [
                    'total' => $fertilizantes->total(),
                    'per_page' => $fertilizantes->perPage(),
                    'current_page' => $fertilizantes->currentPage(),
                    'last_page' => $fertilizantes->lastPage(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener fertilizantes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre_comercial' => 'required|string|max:200',
                'tipo' => 'required|in:nitrogenado,fosfatado,potasico,compuesto_npk,organico,micronutrientes,foliar',
                'composicion_quimica' => 'nullable|array',
                'concentracion_npk' => 'nullable|string|max:100',
                'presentacion' => 'required|string|max:100',
                'unidad_medida' => 'required|string|max:50',
                'registro_senasa' => 'nullable|string|max:100',
                'activo' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $fertilizante = AgriFertilizante::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Fertilizante creado exitosamente',
                'data' => $fertilizante
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear fertilizante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $fertilizante = AgriFertilizante::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $fertilizante
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fertilizante no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $fertilizante = AgriFertilizante::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre_comercial' => 'sometimes|string|max:200',
                'tipo' => 'sometimes|in:nitrogenado,fosfatado,potasico,compuesto_npk,organico,micronutrientes,foliar',
                'composicion_quimica' => 'nullable|array',
                'concentracion_npk' => 'nullable|string|max:100',
                'presentacion' => 'sometimes|string|max:100',
                'unidad_medida' => 'sometimes|string|max:50',
                'registro_senasa' => 'nullable|string|max:100',
                'activo' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $fertilizante->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Fertilizante actualizado exitosamente',
                'data' => $fertilizante
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar fertilizante',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $fertilizante = AgriFertilizante::findOrFail($id);
            $fertilizante->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fertilizante eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar fertilizante',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
