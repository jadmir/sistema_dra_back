<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreMercado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PreMercadoController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/precios/mercados
     */
    public function index()
    {
        try {
            $mercados = PreMercado::with(['ubicacion'])
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'message' => 'Lista de mercados',
                'data' => $mercados
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener mercados',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/precios/mercados
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:MAYORISTA,MINORISTA',
            'direccion' => 'nullable|string',
            'zona' => 'nullable|string|max:100',
            'ubicacion_id' => 'nullable|exists:pre_geo_ubicacion,id',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'telefono' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mercado = PreMercado::create([
                'nombre' => $request->nombre,
                'tipo' => $request->tipo,
                'direccion' => $request->direccion,
                'zona' => $request->zona,
                'ubicacion_id' => $request->ubicacion_id,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'telefono' => $request->telefono,
                'usuario_id' => Auth::id(),
                'estado' => true
            ]);

            $mercado->load('ubicacion');

            return response()->json([
                'message' => 'Mercado creado exitosamente',
                'data' => $mercado
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear mercado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/precios/mercados/{id}
     */
    public function show(string $id)
    {
        try {
            $mercado = PreMercado::with(['ubicacion', 'usuario'])
                ->findOrFail($id);

            return response()->json([
                'message' => 'Detalle del mercado',
                'data' => $mercado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Mercado no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/precios/mercados/{id}
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:MAYORISTA,MINORISTA',
            'direccion' => 'nullable|string',
            'zona' => 'nullable|string|max:100',
            'ubicacion_id' => 'nullable|exists:pre_geo_ubicacion,id',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'telefono' => 'nullable|string|max:20',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mercado = PreMercado::findOrFail($id);

            $mercado->update([
                'nombre' => $request->nombre,
                'tipo' => $request->tipo,
                'direccion' => $request->direccion,
                'zona' => $request->zona,
                'ubicacion_id' => $request->ubicacion_id,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'telefono' => $request->telefono,
                'estado' => $request->estado ?? $mercado->estado
            ]);

            $mercado->load('ubicacion');

            return response()->json([
                'message' => 'Mercado actualizado exitosamente',
                'data' => $mercado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar mercado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/precios/mercados/{id}
     */
    public function destroy(string $id)
    {
        try {
            $mercado = PreMercado::findOrFail($id);
            $mercado->delete();

            return response()->json([
                'message' => 'Mercado eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar mercado',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
