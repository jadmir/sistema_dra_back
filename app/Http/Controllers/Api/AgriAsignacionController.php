<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgriAsignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgriAsignacionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AgriAsignacion::with(['encuestador', 'supervisor']);

            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('encuestador_id')) {
                $query->encuestador($request->encuestador_id);
            }

            if ($request->has('supervisor_id')) {
                $query->supervisor($request->supervisor_id);
            }

            if ($request->has('tipo_formulario')) {
                $query->tipoFormulario($request->tipo_formulario);
            }

            if ($request->has('provincia')) {
                $query->provincia($request->provincia);
            }

            if ($request->has('vigentes') && $request->vigentes == 'true') {
                $query->vigentes();
            }

            $sortBy = $request->get('sort_by', 'fecha_inicio');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 15);
            $asignaciones = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $asignaciones->items(),
                'pagination' => [
                    'total' => $asignaciones->total(),
                    'per_page' => $asignaciones->perPage(),
                    'current_page' => $asignaciones->currentPage(),
                    'last_page' => $asignaciones->lastPage(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener asignaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'encuestador_id' => 'required|exists:agri_encuestadores_insumos,id',
                'supervisor_id' => 'required|exists:agri_supervisores_insumos,id',
                'tipo_formulario' => 'required|in:F-1,F-4,F-6,F-14',
                'provincia' => 'required|string|max:100',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'nullable|date|after:fecha_inicio',
                'estado' => 'nullable|in:activa,finalizada,suspendida',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $asignacion = AgriAsignacion::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Asignación creada exitosamente',
                'data' => $asignacion->load(['encuestador', 'supervisor'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear asignación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $asignacion = AgriAsignacion::with(['encuestador', 'supervisor'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $asignacion
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Asignación no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $asignacion = AgriAsignacion::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'encuestador_id' => 'sometimes|exists:agri_encuestadores_insumos,id',
                'supervisor_id' => 'sometimes|exists:agri_supervisores_insumos,id',
                'tipo_formulario' => 'sometimes|in:F-1,F-4,F-6,F-14',
                'provincia' => 'sometimes|string|max:100',
                'fecha_inicio' => 'sometimes|date',
                'fecha_fin' => 'nullable|date|after:fecha_inicio',
                'estado' => 'sometimes|in:activa,finalizada,suspendida',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $asignacion->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Asignación actualizada exitosamente',
                'data' => $asignacion->load(['encuestador', 'supervisor'])
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar asignación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $asignacion = AgriAsignacion::findOrFail($id);
            $asignacion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asignación eliminada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar asignación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
