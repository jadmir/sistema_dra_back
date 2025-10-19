<?php

namespace App\Http\Controllers;

use App\Models\AgriAnimales;
use Illuminate\Http\Request;

class AgriAnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 10);

            $animales = AgriAnimales::with(['usuario', 'variedad'])
                ->where('estado', true)
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return response()->json([
                'message' => 'Lista de animales activos.',
                'data' => $animales
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al listar los animales.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:agri_animales,codigo',
            'variedad_id' => 'nullable|exists:agri_variedads,id',
            'edad' => 'nullable|integer|min:0',
            'peso' => 'nullable|numeric|min:0',
            'estado' => 'boolean',
        ], [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique' => 'El código ya existe.',
        ]);

        try {
            $user = $request->user();
            if ($user) {
                $validated['usuario_id'] = $user->id;
            }

            $validated['estado'] = $validated['estado'] ?? true;

            $animal = AgriAnimales::create($validated);

            return response()->json([
                'message' => 'Animal registrado correctamente.',
                'data' => $animal
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo registrar el animal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $animal = AgriAnimales::with(['usuario', 'variedad'])->find($id);

            if (!$animal) {
                return response()->json(['message' => 'Animal no encontrado.'], 404);
            }

            return response()->json([
                'message' => 'Detalle del animal encontrado.',
                'data' => $animal
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener el detalle del animal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'codigo' => "sometimes|required|string|max:50|unique:agri_animales,codigo,{$id}",
            'variedad_id' => 'nullable|exists:agri_variedads,id',
            'edad' => 'nullable|integer|min:0',
            'peso' => 'nullable|numeric|min:0',
            'estado' => 'boolean',
        ]);

        try {
            $animal = AgriAnimales::find($id);
            if (!$animal) {
                return response()->json(['message' => 'Animal no encontrado.'], 404);
            }

            $user = $request->user();
            if ($user) {
                $validated['usuario_id'] = $user->id;
            }

            $animal->update($validated);

            return response()->json([
                'message' => 'Animal actualizado correctamente.',
                'data' => $animal
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo actualizar el animal.',
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
            $animal = AgriAnimales::find($id);

            if (!$animal) {
                return response()->json(['message' => 'Animal no encontrado.'], 404);
            }

            $animal->estado = false;
            $animal->save();

            return response()->json([
                'message' => 'Animal desactivado correctamente.',
                'data' => $animal
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo desactivar el animal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            $term = trim((string) ($request->input('q')
                ?? $request->input('codigo')
                ?? $request->input('query')
                ?? ''));

            $query = AgriAnimales::where('estado', true);

            if ($term !== '') {
                $query->where('codigo', 'like', "%{$term}%");
            }

            if ($request->filled('peso')) {
                $query->where('peso', (float) $request->input('peso'));
            }

            $animales = $query->orderBy('id', 'desc')->paginate($perPage);

            if ($animales->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron animales con los criterios dados.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Resultados de la búsqueda.',
                'data' => $animales
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al realizar la búsqueda.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
