<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PreProductoController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = PreProducto::with(['categoria', 'usuario']);

            if ($request->has('categoria_id')) {
                $query->where('categoria_id', $request->categoria_id);
            }

            if ($request->has('estado')) {
                $estado = filter_var($request->estado, FILTER_VALIDATE_BOOLEAN);
                $query->where('estado', $estado);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('codigo', 'like', "%{$search}%");
                });
            }

            $productos = $query->orderBy('nombre')->get();

            return response()->json($productos);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categoria_id' => 'required|exists:pre_categorias,id',
            'nombre' => 'required|string|max:150',
            'codigo' => 'nullable|string|max:20|unique:pre_productos,codigo',
            'unidad_medida' => 'required|string|max:50',
            'equivalencia_kg' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $producto = PreProducto::create([
                'categoria_id' => $request->categoria_id,
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'unidad_medida' => $request->unidad_medida,
                'equivalencia_kg' => $request->equivalencia_kg ?? 1,
                'usuario_id' => Auth::id(),
                'estado' => true
            ]);

            $producto->load('categoria');

            return response()->json([
                'message' => 'Producto creado exitosamente',
                'data' => $producto
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $producto = PreProducto::with(['categoria', 'usuario'])->findOrFail($id);
            return response()->json($producto);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'categoria_id' => 'sometimes|exists:pre_categorias,id',
            'nombre' => 'sometimes|string|max:150',
            'codigo' => 'sometimes|string|max:20|unique:pre_productos,codigo,'.$id,
            'unidad_medida' => 'sometimes|string|max:50',
            'equivalencia_kg' => 'nullable|numeric|min:0',
            'estado' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $producto = PreProducto::findOrFail($id);
            $producto->update($request->all());
            $producto->load('categoria');

            return response()->json([
                'message' => 'Producto actualizado exitosamente',
                'data' => $producto
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $producto = PreProducto::findOrFail($id);

            // Soft delete
            $producto->delete();

            return response()->json([
                'message' => 'Producto eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
