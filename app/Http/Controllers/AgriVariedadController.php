<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgriVariedad;

class AgriVariedadController extends Controller
{
    // Listar variedades con paginacion 
    public function index(Request $request) {
        try {
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            $variedades = AgriVariedad::where('estado', true)
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return response()->json([
                'message' => 'Lista de variedades activas.',
                'data' => $variedades
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al listar las variedades.'], 500);
        }
    }

    // Crear
    public function store(Request $request) {

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean',
            'producto_id' => 'nullable|exists:agri_productos,id',
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

            $variedad = AgriVariedad::create($validated);

            return response()->json([
                'message' => 'Variedad creada correctamente.',
                'data' => $variedad
            ], 201);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo crear la variedad.',
        'error' => $e->getMessage()], 500);
        }
    }

    public function show($id) {
        try {
            $variedad = AgriVariedad::with(['producto', 'usuario'])->find($id);
            if (!$variedad) {
                return response()->json(['message' => 'Variedad no encontrada.'], 404);
            }
            return response()->json([
                'message' => 'Variedad encontrada.',
                'data' => $variedad
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al obtener la variedad.'], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $variedad = AgriVariedad::find($id);
            if (!$variedad) {
                return response()->json(['message' => 'Variedad no encontrada.'], 404);
            }

            $validated = $request->validate([
                'nombre' => 'sometimes|required|string|max:150',
                'descripcion' => 'nullable|string',
                'estado' => 'boolean',
                'producto_id' => 'nullable|exists:agri_productos,id',
            ]);

            $variedad->update($validated);

            return response()->json([
                'message' => 'Variedad actualizada correctamente.',
                'data' => $variedad
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo actualizar la variedad.'], 500);
        }
    }

    public function destroy($id) {
        try {
            $variedad = AgriVariedad::find($id);
            if (!$variedad) {
                return response()->json(['message' => 'Variedad no encontrada.'], 404);
            }
            $variedad->update(['estado' => false]);

            return response()->json(['message' => 'Variedad desactivada correctamente.'], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo desactivar la variedad.'], 500);
        }
    }

    public function search(Request $request) {
        try {
            $term = trim((string) ($request->input('q') ?? $request->input('query') ?? ''));
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            $query = AgriVariedad::query()->where('estado', true);

            if ($term !== '') {
                $query->where(function ($q) use ($term) {
                    $q->where('nombre', 'like', "%{$term}%")
                      ->orWhere('descripcion', 'like', "%{$term}%");
                });
            }

            $variedades = $query->orderBy('id', 'desc')->paginate($perPage);

            if ($variedades->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron variedades.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Resultados de la búsqueda.',
                'data' => $variedades
            ], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al realizar la búsqueda.'], 500);
        }
    }
}
