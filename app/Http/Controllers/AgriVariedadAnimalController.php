<?php

namespace App\Http\Controllers;

use App\Models\AgriVariedadAnimal;
use Illuminate\Http\Request;

class AgriVariedadAnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = AgriVariedadAnimal::query();

        if ($request->has('search')) {
            $query->where('nombre', 'like', "%{$request->search}%");
        }

        $variedades = $query->paginate($perPage);
        return response()->json($variedades);
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
            'nombre.max' => 'El nombre no debe exceder los 150 caracteres.',
        ]);

        try {
            $validated['estado'] = $validated['estado'] ?? true;

            $variedadAnimal = AgriVariedadAnimal::create($validated);

            return response()->json([
                'message' => 'Variedad animal creada correctamente.',
                'data' => $variedadAnimal
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo crear la variedad animal.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $variedadAnimal = AgriVariedadAnimal::find($id);

        if (!$variedadAnimal) {
            return response()->json(['message' => 'Variedad animal no encontrada.'], 404);
        }

        return response()->json([
            'message' => 'Variedad animal encontrada.',
            'data' => $variedadAnimal
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $variedadAnimal = AgriVariedadAnimal::find($id);

            if (!$variedadAnimal) {
                return response()->json(['message' => 'Variedad animal no encontrada.'], 404);
            }

            $validated = $request->validate([
                'nombre' => 'required|string|max:150',
                'descripcion' => 'nullable|string',
                'estado' => 'required|boolean',
            ]);

            $variedadAnimal->update($validated);

            return response()->json([
                'message' => 'Variedad animal actualizada exitosamente.',
                'data' => $variedadAnimal
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al actualizar la variedad animal.',
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
            $variedadAnimal = AgriVariedadAnimal::find($id);

            if (!$variedadAnimal) {
                return response()->json(['message' => 'Variedad animal no encontrada.'], 404);
            }

            $variedadAnimal->update(['estado' => false]);

            return response()->json([
                'message' => 'Variedad animal desactivada correctamente.'
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al eliminar la variedad animal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
{
    try {
        $term = trim((string) ($request->input('q') ?? $request->input('query') ?? ''));
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min(100, $perPage));

        $query = AgriVariedadAnimal::query()->where('estado', true);

        if ($term !== '') {
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'like', "%{$term}%")
                  ->orWhere('descripcion', 'like', "%{$term}%");
            });
        }

        $results = $query->orderBy('id', 'desc')->paginate($perPage);

        if ($results->total() === 0) {
            return response()->json([
                'message' => 'No se encontraron variedades animales.',
                'data' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Resultados de la búsqueda.',
            'data' => $results
        ], 200);

    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Error al realizar la búsqueda.',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
