<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgriNatalidadMortalidad;
use App\Exports\AgriNatalidadMortalidadExport;
use Maatwebsite\Excel\Facades\Excel;

class AgriNatalidadMortalidadController extends Controller
{
    // Listado general
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 10);
            $search = $request->input('search');
            $estado = $request->input('estado');

            $query = AgriNatalidadMortalidad::query();

            if (!is_null($estado)) {
                $query->where('estado', filter_var($estado, FILTER_VALIDATE_BOOLEAN));
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('concepto', 'LIKE', "%{$search}%")
                        ->orWhere('observaciones', 'LIKE', "%{$search}%");
                });
            }

            $items = $query->orderByDesc('id')->paginate($perPage);

            return response()->json([
                'message' => 'Lista de registros',
                'meta' => [
                    'total' => $items->total(),
                    'current_page' => $items->currentPage(),
                    'last_page' => $items->lastPage(),
                ],
                'data' => $items->items()
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al listar los registros.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Crear nuevo registro
    public function store(Request $request)
    {
        $validated = $request->validate([
            'concepto' => 'required|string|max:100',
            'observaciones' => 'nullable|string',
            'estado' => 'boolean',
        ]);

        try {
            $user = $request->user();
            if ($user) $validated['usuario_id'] = $user->id;

            $validated['estado'] = $validated['estado'] ?? true;

            $item = AgriNatalidadMortalidad::create($validated);

            return response()->json(
                [
                    'message' => 'Registro creado correctamente.',
                    'data' => $item
                ],
                201
            );
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'message' => 'No se pudo crear el registro.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    // Mostrar un registro
    public function show($id)
    {
        try {
            $item = AgriNatalidadMortalidad::with('usuario')->find($id);
            if (!$item || !$item->estado) {
                return response()->json(
                    [
                        'message' => 'Registro no encontrado o inactivo.'
                    ],
                    404
                );
            }

            $item->loadMissing('usuario');

            return response()->json(
                [
                    'message' => 'Registro encontrado.',
                    'data' => $item
                ],
                200
            );
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'message' => 'Error al obtener el registro.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    // Actualizar registro
    public function update(Request $request, $id)
    {


        try {
            $item = AgriNatalidadMortalidad::find($id);

            if (!$item) {
                return response()->json(['message' => 'Registro no encontrada.'], 404);
            }

            $validated = $request->validate([
                'concepto' => 'sometimes|required|string|max:100',
                'observaciones' => 'nullable|string',
                'estado' => 'boolean',
            ]);

            $user = $request->user();
            if ($user) {
                $validated['usuario_id'] = $user->id;
            }

            $item->update($validated);

            return response()->json(
                [
                    'message' => 'Registro actualizado.',
                    'data' => $item
                ],
                200
            );
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'message' => 'No se pudo actualizar el registro.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    // Eliminación lógica
    public function destroy($id, Request $request)
    {
        try {
            $item = AgriNatalidadMortalidad::find($id);

            if (!$item) {
                return response()->json(['message' => 'Registro no encontrado.'], 404);
            }

            // Obtener el nuevo estado que envía el front (true o false)
            $nuevoEstado = $request->boolean('estado', false);

            $item->update(['estado' => $nuevoEstado]);

            return response()->json([
                'message' => $nuevoEstado
                    ? 'Registro activado correctamente.'
                    : 'Registro desactivado correctamente.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al actualizar el estado del registro.',
                'error' => $e->getMessage()
            ], 500);
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
                    $q->Where('concepto', 'like', "%{$term}%")
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
                'meta' => [
                    'total' => $resultados->total(),
                    'current_page' => $resultados->currentPage(),
                    'last_page' => $resultados->lastPage(),
                ],
                'data' => $resultados->items()
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
        return Excel::download(new AgriNatalidadMortalidadExport($filters), 'natalidad_mortalidad.xlsx');
    }
}
