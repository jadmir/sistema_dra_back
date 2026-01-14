<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgriSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgriSupervisorController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AgriSupervisor::with(['encuestas', 'asignaciones']);

            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('region')) {
                $query->region($request->region);
            }

            if ($request->has('provincia')) {
                $query->provincia($request->provincia);
            }

            if ($request->has('activos') && $request->activos == 'true') {
                $query->activos();
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nombres', 'like', "%{$search}%")
                      ->orWhere('apellido_paterno', 'like', "%{$search}%")
                      ->orWhere('apellido_materno', 'like', "%{$search}%")
                      ->orWhere('dni', 'like', "%{$search}%");
                });
            }

            $sortBy = $request->get('sort_by', 'nombres');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 15);
            $supervisores = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $supervisores->items(),
                'pagination' => [
                    'total' => $supervisores->total(),
                    'per_page' => $supervisores->perPage(),
                    'current_page' => $supervisores->currentPage(),
                    'last_page' => $supervisores->lastPage(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener supervisores',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombres' => 'required|string|max:100',
                'apellido_paterno' => 'required|string|max:100',
                'apellido_materno' => 'required|string|max:100',
                'dni' => 'required|string|size:8|unique:agri_supervisores_insumos,dni',
                'email' => 'required|email|unique:agri_supervisores_insumos,email',
                'telefono' => 'required|string|max:20',
                'region_supervisada' => 'required|string|max:100',
                'provincias_asignadas' => 'nullable|array',
                'fecha_asignacion' => 'required|date',
                'estado' => 'nullable|in:activo,inactivo,suspendido',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $supervisor = AgriSupervisor::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Supervisor creado exitosamente',
                'data' => $supervisor
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear supervisor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $supervisor = AgriSupervisor::with(['encuestas', 'asignaciones.encuestador'])
                ->findOrFail($id);

            $estadisticas = [
                'encuestas_supervisadas' => $supervisor->encuestasSupervisadas(),
                'encuestas_validadas' => $supervisor->encuestasValidadas(),
                'encuestas_rechazadas' => $supervisor->encuestasRechazadas(),
                'encuestadores_asignados' => $supervisor->encuestadoresAsignados(),
            ];

            return response()->json([
                'success' => true,
                'data' => $supervisor,
                'estadisticas' => $estadisticas
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supervisor no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $supervisor = AgriSupervisor::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombres' => 'sometimes|string|max:100',
                'apellido_paterno' => 'sometimes|string|max:100',
                'apellido_materno' => 'sometimes|string|max:100',
                'dni' => 'sometimes|string|size:8|unique:agri_supervisores_insumos,dni,' . $id,
                'email' => 'sometimes|email|unique:agri_supervisores_insumos,email,' . $id,
                'telefono' => 'sometimes|string|max:20',
                'region_supervisada' => 'sometimes|string|max:100',
                'provincias_asignadas' => 'nullable|array',
                'fecha_asignacion' => 'sometimes|date',
                'estado' => 'sometimes|in:activo,inactivo,suspendido',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $supervisor->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Supervisor actualizado exitosamente',
                'data' => $supervisor
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar supervisor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $supervisor = AgriSupervisor::findOrFail($id);
            $supervisor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supervisor eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar supervisor',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
