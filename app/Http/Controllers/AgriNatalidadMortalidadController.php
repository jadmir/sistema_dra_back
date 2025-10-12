<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AgriNatalidadMortalidad;
use Illuminate\Support\Facades\Auth;

class AgriNatalidadMortalidadController extends Controller
{
    // Listado general
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 10);

            $items = AgriNatalidadMortalidad::where('estado', true)
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return response()->json(['message' => 'Lista de registros activos.', 'data' => $items], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al listar los registros.', 'error' => $e->getMessage()], 500);
        }
    }

    // Crear nuevo registro
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:20',
            'concepto' => 'required|string|max:100',
            'observaciones' => 'nullable|string',
            'estado' => 'boolean',
        ]);

        try {
            $user = $request->user();
            if ($user) $validated['usuario_id'] = $user->id;

            $validated['estado'] = $validated['estado'] ?? true;

            $item = AgriNatalidadMortalidad::create($validated);

            return response()->json(['message' => 'Registro creado correctamente.', 'data' => $item], 201);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo crear el registro.', 'error' => $e->getMessage()], 500);
        }
    }

    // Mostrar un registro
    public function show($id)
    {
        try {
            $item = AgriNatalidadMortalidad::with('usuario')->find($id);
            if (!$item || !$item->estado) {
                return response()->json(['message' => 'Registro no encontrado o inactivo.'], 404);
            }
            return response()->json(['message' => 'Registro encontrado.', 'data' => $item], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al obtener el registro.', 'error' => $e->getMessage()], 500);
        }
    }

    // Actualizar registro
    public function update(Request $request, $id)
    {
         $validated = $request->validate([
            'tipo' => 'sometimes|required|string|max:20',
            'concepto' => 'sometimes|required|string|max:100',
            'observaciones' => 'nullable|string',
            'estado' => 'boolean',
        ]);

        try {
            $item = AgriNatalidadMortalidad::find($id);
            if (!$item) return response()->json(['message' => 'Registro no encontrado.'], 404);

            $item->update($validated);

            return response()->json(['message' => 'Registro actualizado.', 'data' => $item], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo actualizar el registro.', 'error' => $e->getMessage()], 500);
        }
      
    }

    // Eliminación lógica
    public function destroy($id)
    {
        try {
            $variedad = AgriNatalidadMortalidad::find($id);
            if (!$variedad) {
                return response()->json(['message' => 'Registro no encontrada.'], 404);
            }

            $variedad->update(['estado' => false]);

            return response()->json(['message' => 'Registro desactivada correctamente.'], 200);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo desactivar el registro.'], 500);
        }
    }

    // Búsqueda
    public function search(Request $request)
    {
        try {
            $term = trim((string) ($request->input('q') ?? $request->input('query') ?? ''));
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            $query = AgriNatalidadMortalidad::query()->where('estado', true);

            if ($term !== '') {
                $query->where(function ($q) use ($term) {
                    $q->where('tipo', 'like', "%{$term}%")
                    ->orWhere('concepto', 'like', "%{$term}%")
                    ->orWhere('observaciones', 'like', "%{$term}%");
                });
            }

            $resultados = $query->orderBy('id', 'desc')->paginate($perPage);

            if ($resultados->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron registros de natalidad o mortalidad.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Resultados de la búsqueda.',
                'data' => $resultados
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al realizar la búsqueda.',
                'error' => $e->getMessage() // opcional, para depurar
            ], 500);
        }
        }
}
