<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgriSacaClase;
class AgriSacaClaseController extends Controller
{
  
    // Listado
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            $clases = AgriSacaClase::where('estado', 'activo')
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            if ($clases->total() === 0) {
                return response()->json([
                    'message' => 'No hay clases activas.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Lista de clases activas.',
                'data' => $clases
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al obtener las clases.'], 500);
        }
    }

    // Crear
    public function store(Request $request)
    {
    $validated =$request->validate([
        'nombre' => 'required|string|max:150',
        'descripcion' => 'nullable|string',
        'estado' => 'nullable|string|in:activo,inactivo'
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no debe exceder 150 caracteres.',
    ]);

    try {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        $validated['usuario_id'] = $user->id;
        $validated['estado'] = $validated['estado'] ?? 'activo';

        $clase = AgriSacaClase::create($validated);

        return response()->json([
            'message' => 'Clase creada correctamente.',
            'data' => $clase
        ], 201);
    } catch (\Throwable $e) {
        return response()->json(['message' => 'No se pudo crear la clase.'], 500);
    }
    }

    // Mostrar
    public function show($id)
    {
        $clase = AgriSacaClase::find($id);
        if (!$clase || $clase->estado !== 'activo') {
            return response()->json(['message' => 'Clase no encontrada o inactiva.'], 404);
        }

        $clase->loadMissing('usuario');

        return response()->json([
            'message' => 'Clase encontrada.',
            'data' => $clase
        ], 200);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $clase = AgriSacaClase::find($id);

        if (!$clase) {
            return response()->json(['message' => 'Clase no encontrada.'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string|in:activo,inactivo'
        ]);

        try {
            $clase->fill($validated);
            $clase->save();

            return response()->json([
                'message' => 'Clase actualizada correctamente.',
                'data' => $clase
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo actualizar la clase.'], 500);
        }
    }

    // Eliminar
    public function destroy($id)
    {
        $clase = AgriSacaClase::find($id);
        if (!$clase) {
            return response()->json(['message' => 'Clase no encontrada.'], 404);
        }

        try {
            $clase->estado = 'inactivo';
            $clase->save();

            return response()->json(['message' => 'Clase desactivada correctamente.'], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo desactivar la clase.'], 500);
        }
    }

    // Búsqueda avanzada
    public function search(Request $request)
    {
        try {
            $term = trim((string) ($request->input('q') ?? $request->input('query') ?? ''));
            
            $hasFilters = $term !== '' || $request->filled('nombre') || $request->filled('estado');

            if (!$hasFilters) {
                return response()->json([
                    'message' => 'Debe especificar al menos un nombre o descripcion',
                    'data' => []
                ], 400);
            }
            
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            $query = AgriSacaClase::query()->where('estado', true);

            if ($term !== '') {
                $query->where(function ($q) use ($term) {
                    $q->where('nombre', 'like', "%{$term}%")
                      ->orWhere('descripcion', 'like', "%{$term}%");
                });
            }

            $clases = $query->orderBy('id', 'desc')->paginate($perPage);

            if ($clases->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron clases.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Resultados de la búsqueda.',
                'data' => $clases
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al realizar la búsqueda.'
            ], 500);
        }
    }
}