<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PreCategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/precios/categorias
     */
    public function index()
    {
        try {
            $categorias = PreCategoria::with(['usuario', 'productos'])
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'message' => 'Lista de categorías',
                'data' => $categorias
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener categorías',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/precios/categorias
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10|unique:pre_categorias,codigo'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $categoria = PreCategoria::create([
                'nombre' => $request->nombre,
                'codigo' => strtoupper($request->codigo),
                'usuario_id' => Auth::id(),
                'estado' => true
            ]);

            return response()->json([
                'message' => 'Categoría creada exitosamente',
                'data' => $categoria
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/precios/categorias/{id}
     */
    public function show(string $id)
    {
        try {
            $categoria = PreCategoria::with(['usuario', 'productos'])
                ->findOrFail($id);

            return response()->json([
                'message' => 'Detalle de la categoría',
                'data' => $categoria
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Categoría no encontrada',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/precios/categorias/{id}
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10|unique:pre_categorias,codigo,' . $id,
            'estado' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $categoria = PreCategoria::findOrFail($id);

            $categoria->update([
                'nombre' => $request->nombre,
                'codigo' => strtoupper($request->codigo),
                'estado' => $request->estado ?? $categoria->estado
            ]);

            return response()->json([
                'message' => 'Categoría actualizada exitosamente',
                'data' => $categoria
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/precios/categorias/{id}
     */
    public function destroy(string $id)
    {
        try {
            $categoria = PreCategoria::findOrFail($id);
            
            // Verificar si tiene productos asociados
            if ($categoria->productos()->count() > 0) {
                return response()->json([
                    'message' => 'No se puede eliminar la categoría porque tiene productos asociados'
                ], 400);
            }

            $categoria->delete();

            return response()->json([
                'message' => 'Categoría eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
