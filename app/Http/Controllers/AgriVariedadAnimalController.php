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
            $search = trim($request->search);
            $query->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
        }

         $variedades = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'message' => 'Listado de variedades animales.',
            'meta' => [
                'total' => $variedades->total(),
                'current_page' => $variedades->currentPage(),
                'last_page' => $variedades->lastPage(),
            ],
            'data' => $variedades->items(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150|unique:agri_variedad_animal,nombre',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean'
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'El nombre ya está registrado.',
            'nombre.max' => 'El nombre no debe exceder los 150 caracteres.'
        ]);

        try {
            $user = $request->user();
            if ($user) {
                $validated['usuario_id'] = $user->id;
            }
            
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

        $variedadAnimal->loadMissing('usuario');

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
                'nombre' => 'required|string|max:150|unique:agri_variedad_animal,nombre',
                'descripcion' => 'nullable|string',
                'estado' => 'required|boolean',
            ]);

            $user = $request->user();
            if ($user) {
                $validated['usuario_id'] = $user->id;
            }

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

            $query = AgriVariedadAnimal::where('estado', true);

            if ($term !== '') {
                $query->where(function ($q) use ($term) {
                    $q->where('nombre', 'like', "%{$term}%")
                    ->orWhere('descripcion', 'like', "%{$term}%");
                });
            }

            $results = $query->orderBy('id', 'desc')->paginate($perPage);

            return response()->json([
                'message' => 'Resultados de la búsqueda.',
                'meta' => [
                    'total' => $results->total(),
                    'current_page' => $results->currentPage(),
                    'last_page' => $results->lastPage(),
                ],
                'data' => $results->items()
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al realizar la búsqueda.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
