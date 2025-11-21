<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreEncuestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PreEncuestadorController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/precios/encuestadores
     */
    public function index()
    {
        try {
            $encuestadores = PreEncuestador::with(['usuario'])
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'message' => 'Lista de encuestadores',
                'data' => $encuestadores
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener encuestadores',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/precios/encuestadores
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:50|unique:pre_encuestadores,codigo',
            'nombre' => 'required|string|max:255',
            'dni' => 'required|string|max:8',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'usuario_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $encuestador = PreEncuestador::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'dni' => $request->dni,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'usuario_id' => $request->usuario_id,
                'estado' => true
            ]);

            return response()->json([
                'message' => 'Encuestador creado exitosamente',
                'data' => $encuestador
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear encuestador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/precios/encuestadores/{id}
     */
    public function show(string $id)
    {
        try {
            $encuestador = PreEncuestador::with(['usuario', 'muestras'])
                ->findOrFail($id);

            return response()->json([
                'message' => 'Detalle del encuestador',
                'data' => $encuestador
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Encuestador no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/precios/encuestadores/{id}
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'required|string|max:50|unique:pre_encuestadores,codigo,' . $id,
            'nombre' => 'required|string|max:255',
            'dni' => 'required|string|max:8',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'usuario_id' => 'nullable|exists:users,id',
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $encuestador = PreEncuestador::findOrFail($id);

            $encuestador->update([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'dni' => $request->dni,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'usuario_id' => $request->usuario_id,
                'estado' => $request->estado ?? $encuestador->estado
            ]);

            return response()->json([
                'message' => 'Encuestador actualizado exitosamente',
                'data' => $encuestador
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar encuestador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/precios/encuestadores/{id}
     */
    public function destroy(string $id)
    {
        try {
            $encuestador = PreEncuestador::findOrFail($id);
            $encuestador->delete();

            return response()->json([
                'message' => 'Encuestador eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar encuestador',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
