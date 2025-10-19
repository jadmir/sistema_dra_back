<?php

namespace App\Http\Controllers;

use App\Models\SubSector;
use Illuminate\Http\Request;

class SubSectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Permite personalizar la cantidad de registros por página (por defecto 10)
            $perPage = (int)$request->input('per_page', 10);

            $subSectores = SubSector::activos()
                ->with('grupos.subgrupos.cultivos')
                ->orderBy('codigo')
                ->where('estado', 1)
                ->paginate($perPage);

            if ($subSectores->total() === 0) {
                return response()->json([
                    'message' => 'No hay subsectores activos.',
                    'data'    => $subSectores
                ], 200);
            }

            return response()->json($subSectores, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener la lista de subsectores.',
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mensajes = [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique'   => 'El código ya existe.',
        ];

        $data = $request->validate([
            'codigo'      => 'required|string|max:100|unique:sub_sectores,codigo',
            'descripcion' => 'nullable|string|max:200',
        ], $mensajes);

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }

            $data['usuario_id'] = $user->id;
            $data['estado']     = 1;

            $subSector = SubSector::create($data);

            return response()->json([
                'message' => 'Subsector creado correctamente',
                'data'    => $subSector
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo crear el subsector.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $subsector = SubSector::with('grupos.subgrupos.cultivos')
                ->where('estado', 1) // solo activos
                ->find($id);

            if (!$subsector) {
                return response()->json(['message' => 'Subsector no encontrado.'], 404);
            }

            return response()->json($subsector, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener el subsector.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubSector $subSector)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mensajes = [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.unique'   => 'El código ya existe.',
        ];

        $data = $request->validate([
            'codigo'      => 'required|string|max:100|unique:sub_sectores,codigo,' . $id,
            'descripcion' => 'nullable|string|max:200',
        ], $mensajes);

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }

            $subSector = SubSector::find($id);
            if (!$subSector) {
                return response()->json(['message' => 'Subsector no encontrado.'], 404);
            }

            // Bloquear actualización si está inactivo
            if ((int)$subSector->estado === 0) {
                return response()->json([
                    'message' => 'El subsector está desactivado y no puede actualizarse.'
                ], 409);
            }

            $data['usuario_id'] = $user->id;

            $subSector->fill($data);

            if (!$subSector->isDirty()) {
                return response()->json(['message' => 'No hay cambios para aplicar.'], 422);
            }

            $subSector->save();

            return response()->json([
                'message' => 'Subsector actualizado correctamente',
                'data'    => $subSector
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo actualizar el subsector.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $subSector = SubSector::find($id);
            if (!$subSector) {
                return response()->json([
                    'message' => 'Subsector no encontrado.'
                ], 404);
            }

            // Baja lógica: marcar como inactivo
            if ((int)$subSector->estado === 0) {
                return response()->json([
                    'message' => 'El subsector ya está inactivo.'
                ], 200);
            }

            $subSector->estado = 0; // false/inactivo
            $subSector->save();

            return response()->json([
                'message' => 'Subsector desactivado correctamente.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al desactivar el subsector.'
            ], 500);
        }
    }


    // Buscar subsectores por código o descripción o usuario

    public function search(Request $request)
    {
        try {
            // Parámetros
            $term = trim((string) ($request->query('search') ?? $request->query('q') ?? ''));
            $usuarioId = $request->query('usuario_id');
            $perPage = (int) $request->query('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            // Si no viene ningún filtro, no te devuelvo todo para que no parezca un index
            if ($term === '' && empty($usuarioId)) {
                return response()->json([
                    'message' => 'Debes enviar al menos un parámetro de búsqueda (search o usuario_id).',
                    'data'    => []
                ], 422);
            }

            $q = SubSector::query()
                ->with('grupos.subgrupos.cultivos')
                ->where('estado', 1);

            if ($term !== '') {
                $q->where(function ($w) use ($term) {
                    $w->where('codigo', 'like', "%{$term}%")
                      ->orWhere('descripcion', 'like', "%{$term}%");
                });
            }

            if (!empty($usuarioId)) {
                $q->where('usuario_id', (int) $usuarioId);
            }

            $result = $q->orderBy('codigo')->paginate($perPage);

            if ($result->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron subsectores con los criterios dados.',
                    'data'    => $result
                ], 200);
            }

            return response()->json([
                'message' => 'Resultados de la búsqueda',
                'data'    => $result
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al buscar subsectores.'
            ], 500);
        }
    }
}
