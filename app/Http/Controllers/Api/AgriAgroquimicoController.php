<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgriAgroquimico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgriAgroquimicoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AgriAgroquimico::query();

            if ($request->has('tipo')) {
                $query->tipo($request->tipo);
            }

            if ($request->has('categoria_toxicologica')) {
                $query->categoriaToxicologica($request->categoria_toxicologica);
            }

            if ($request->has('ingrediente_activo')) {
                $query->ingredienteActivo($request->ingrediente_activo);
            }

            if ($request->has('cultivo_objetivo')) {
                $query->cultivoObjetivo($request->cultivo_objetivo);
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
            $agroquimicos = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $agroquimicos->items(),
                'pagination' => [
                    'total' => $agroquimicos->total(),
                    'per_page' => $agroquimicos->perPage(),
                    'current_page' => $agroquimicos->currentPage(),
                    'last_page' => $agroquimicos->lastPage(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener agroquímicos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre_comercial' => 'required|string|max:200',
                'ingrediente_activo' => 'required|string|max:200',
                'tipo' => 'required|in:herbicida,insecticida,fungicida,acaricida,nematicida,rodenticida,otros',
                'categoria_toxicologica' => 'required|in:Ia,Ib,II,III,IV',
                'concentracion' => 'nullable|string|max:100',
                'formulacion' => 'nullable|string|max:100',
                'unidad_medida' => 'required|string|max:50',
                'registro_senasa' => 'nullable|string|max:100',
                'cultivos_objetivo' => 'nullable|array',
                'plagas_objetivo' => 'nullable|array',
                'activo' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $agroquimico = AgriAgroquimico::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Agroquímico creado exitosamente',
                'data' => $agroquimico
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear agroquímico',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $agroquimico = AgriAgroquimico::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $agroquimico
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Agroquímico no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $agroquimico = AgriAgroquimico::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre_comercial' => 'sometimes|string|max:200',
                'ingrediente_activo' => 'sometimes|string|max:200',
                'tipo' => 'sometimes|in:herbicida,insecticida,fungicida,acaricida,nematicida,rodenticida,otros',
                'categoria_toxicologica' => 'sometimes|in:Ia,Ib,II,III,IV',
                'concentracion' => 'nullable|string|max:100',
                'formulacion' => 'nullable|string|max:100',
                'unidad_medida' => 'sometimes|string|max:50',
                'registro_senasa' => 'nullable|string|max:100',
                'cultivos_objetivo' => 'nullable|array',
                'plagas_objetivo' => 'nullable|array',
                'activo' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $agroquimico->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Agroquímico actualizado exitosamente',
                'data' => $agroquimico
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar agroquímico',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $agroquimico = AgriAgroquimico::findOrFail($id);
            $agroquimico->delete();

            return response()->json([
                'success' => true,
                'message' => 'Agroquímico eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar agroquímico',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
