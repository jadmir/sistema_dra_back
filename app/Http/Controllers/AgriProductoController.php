<?php

namespace App\Http\Controllers;

use App\Models\AgriProducto;
use Illuminate\Http\Request;

class AgriProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            $productos = AgriProducto::where('estado', true)
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return response()->json([
                'message' => 'Lista de productos activos.',
                'data' => $productos
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al listar los productos.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no debe exceder 150 caracteres.',
        ]);

        try {
            $user = $request->user();
            if ($user) {
                $validated['usuario_id'] = $user->id;
            }

            $validated['estado'] = $validated['estado'] ?? true;

            $producto = AgriProducto::create($validated);

            return response()->json([
                'message' => 'Producto creado correctamente.',
                'data' => $producto
            ], 201);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo crear el producto.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $producto = AgriProducto::with(['usuario'])->find($id);
            if (!$producto) {
                return response()->json(['message' => 'Producto no encontrado.'], 404);
            }

            return response()->json([
                'message' => 'Producto encontrado.',
                'data' => $producto
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al obtener el producto.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $producto = AgriProducto::find($id);
            if (!$producto) {
                return response()->json(['message' => 'Producto no encontrado.'], 404);
            }

            $validated = $request->validate([
                'nombre' => 'sometimes|required|string|max:150',
                'descripcion' => 'nullable|string',
                'estado' => 'boolean',
            ]);

            $producto->update($validated);

            return response()->json([
                'message' => 'Producto actualizado correctamente.',
                'data' => $producto
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo actualizar el producto.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $producto = AgriProducto::find($id);
            if (!$producto) {
                return response()->json(['message' => 'Producto no encontrado.'], 404);
            }

            $producto->update(['estado' => false]);

            return response()->json(['message' => 'Producto desactivado correctamente.'], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo desactivar el producto.'], 500);
        }
    }

    //search
    public function search(Request $request)
    {
        try {
            $term = trim((string) ($request->input('q') ?? $request->input('query') ?? ''));
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            $query = AgriProducto::query()->where('estado', true);

            if ($term !== '') {
                $query->where(function ($q) use ($term) {
                    $q->where('nombre', 'like', "%{$term}%")
                      ->orWhere('descripcion', 'like', "%{$term}%");
                });
            }

            $productos = $query->orderBy('id', 'desc')->paginate($perPage);

            if ($productos->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron productos.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Resultados de la búsqueda.',
                'data' => $productos
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al realizar la búsqueda.'], 500);
        }
    }
}
