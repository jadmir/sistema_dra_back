<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreGeoUbicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PreGeoUbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/precios/ubicaciones
     */
    public function index()
    {
        try {
            $ubicaciones = PreGeoUbicacion::orderBy('departamento')
                ->orderBy('provincia')
                ->orderBy('distrito')
                ->get();

            return response()->json([
                'message' => 'Lista de ubicaciones geográficas',
                'data' => $ubicaciones
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener ubicaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/precios/ubicaciones
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'departamento' => 'required|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'distrito' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ubicacion = PreGeoUbicacion::create([
                'departamento' => $request->departamento,
                'provincia' => $request->provincia,
                'distrito' => $request->distrito,
                'estado' => true
            ]);

            return response()->json([
                'message' => 'Ubicación creada exitosamente',
                'data' => $ubicacion
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear ubicación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/precios/ubicaciones/{id}
     */
    public function show(string $id)
    {
        try {
            $ubicacion = PreGeoUbicacion::with(['mercados', 'reportes'])
                ->findOrFail($id);

            return response()->json([
                'message' => 'Detalle de la ubicación',
                'data' => $ubicacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ubicación no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/precios/ubicaciones/{id}
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'departamento' => 'required|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'distrito' => 'nullable|string|max:100',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ubicacion = PreGeoUbicacion::findOrFail($id);

            $ubicacion->update([
                'departamento' => $request->departamento,
                'provincia' => $request->provincia,
                'distrito' => $request->distrito,
                'estado' => $request->estado ?? $ubicacion->estado
            ]);

            return response()->json([
                'message' => 'Ubicación actualizada exitosamente',
                'data' => $ubicacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar ubicación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/precios/ubicaciones/{id}
     */
    public function destroy(string $id)
    {
        try {
            $ubicacion = PreGeoUbicacion::findOrFail($id);
            
            // Verificar si tiene mercados asociados
            if ($ubicacion->mercados()->count() > 0) {
                return response()->json([
                    'message' => 'No se puede eliminar la ubicación porque tiene mercados asociados'
                ], 400);
            }

            $ubicacion->delete();

            return response()->json([
                'message' => 'Ubicación eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar ubicación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
