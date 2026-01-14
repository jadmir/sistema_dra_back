<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgriEncuestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgriEncuestadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = AgriEncuestador::with(['encuestas', 'asignaciones']);

            // Filtros
            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('provincia')) {
                $query->provincia($request->provincia);
            }

            if ($request->has('especializacion')) {
                $query->especializacion($request->especializacion);
            }

            if ($request->has('activos') && $request->activos == 'true') {
                $query->activos();
            }

            // Búsqueda
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nombres', 'like', "%{$search}%")
                      ->orWhere('apellido_paterno', 'like', "%{$search}%")
                      ->orWhere('apellido_materno', 'like', "%{$search}%")
                      ->orWhere('dni', 'like', "%{$search}%");
                });
            }

            // Ordenamiento
            $sortBy = $request->get('sort_by', 'nombres');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginación
            $perPage = $request->get('per_page', 15);
            $encuestadores = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $encuestadores->items(),
                'pagination' => [
                    'total' => $encuestadores->total(),
                    'per_page' => $encuestadores->perPage(),
                    'current_page' => $encuestadores->currentPage(),
                    'last_page' => $encuestadores->lastPage(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener encuestadores',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombres' => 'required|string|max:100',
                'apellido_paterno' => 'required|string|max:100',
                'apellido_materno' => 'required|string|max:100',
                'dni' => 'required|string|size:8|unique:agri_encuestadores_insumos,dni',
                'email' => 'required|email|unique:agri_encuestadores_insumos,email',
                'telefono' => 'required|string|max:20',
                'especializacion' => 'nullable|array',
                'provincia_asignada' => 'required|string|max:100',
                'fecha_contratacion' => 'required|date',
                'estado' => 'nullable|in:activo,inactivo,suspendido',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // ✅ Laravel convierte automáticamente el array a JSON gracias al cast
            $encuestador = AgriEncuestador::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Encuestador creado exitosamente',
                'data' => $encuestador
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear encuestador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $encuestador = AgriEncuestador::with(['encuestas', 'asignaciones.supervisor'])
                ->findOrFail($id);

            $estadisticas = [
                'encuestas_completadas' => $encuestador->encuestasCompletadas(),
                'encuestas_pendientes' => $encuestador->encuestasPendientes(),
            ];

            return response()->json([
                'success' => true,
                'data' => $encuestador,
                'estadisticas' => $estadisticas
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Encuestador no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $encuestador = AgriEncuestador::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombres' => 'sometimes|string|max:100',
                'apellido_paterno' => 'sometimes|string|max:100',
                'apellido_materno' => 'sometimes|string|max:100',
                'dni' => 'sometimes|string|size:8|unique:agri_encuestadores_insumos,dni,' . $id,
                'email' => 'sometimes|email|unique:agri_encuestadores_insumos,email,' . $id,
                'telefono' => 'sometimes|string|max:20',
                'especializacion' => 'nullable|array',
                'provincia_asignada' => 'sometimes|string|max:100',
                'fecha_contratacion' => 'sometimes|date',
                'estado' => 'sometimes|in:activo,inactivo,suspendido',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // ✅ Laravel convierte automáticamente el array a JSON gracias al cast
            $encuestador->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Encuestador actualizado exitosamente',
                'data' => $encuestador
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar encuestador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $encuestador = AgriEncuestador::findOrFail($id);
            $encuestador->delete();

            return response()->json([
                'success' => true,
                'message' => 'Encuestador eliminado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar encuestador',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
