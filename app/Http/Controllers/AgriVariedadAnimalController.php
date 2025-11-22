<?php

namespace App\Http\Controllers;

use App\Models\AgriVariedadAnimal;
use Illuminate\Http\Request;
use App\Exports\AgriVariedadAnimalExport;
use Maatwebsite\Excel\Facades\Excel;

class AgriVariedadAnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $perPage = max(1, min(100, $perPage));

        $query = AgriVariedadAnimal::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->has('estado') && $request->input('estado') !== '') {
            $estado = filter_var($request->input('estado'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (!is_null($estado)) {
                $query->where('estado', $estado);
            }
        }

        $query->orderBy('id', 'desc');
        $variedades = $query->paginate($perPage);

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
            $validated['estado'] = $validated['estado'] ?? true;
            if ($request->user()) {
                $validated['usuario_id'] = $request->user()->id;
            }

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
        $variedadAnimal = AgriVariedadAnimal::with('usuario')->find($id);

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
                'nombre' => 'required|string|max:150|unique:agri_variedad_animal,nombre,' . $id,
                'descripcion' => 'nullable|string',
                'estado' => 'required|boolean',
            ]);

            if ($request->user()) {
                $validated['usuario_id'] = $request->user()->id;
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
    public function destroy(string $id,  Request $request)
    {
        try {
            $variedadAnimal = AgriVariedadAnimal::find($id);

            if (!$variedadAnimal) {
                return response()->json(['message' => 'Variedad animal no encontrada.'], 404);
            }

            $nuevoEstado = $request->boolean('estado', false);
            $variedadAnimal->update(['estado' => $nuevoEstado]);

            return response()->json([
                'message' => $nuevoEstado
                    ? 'Variedad animal activada correctamente.'
                    : 'Variedad animal desactivada correctamente.'
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

    public function exportExcel(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'estado' => $request->input('estado'),
        ];
        return Excel::download(new AgriVariedadAnimalExport($filters), 'Reporte_Variedad_Animal.xlsx');
    }
}
