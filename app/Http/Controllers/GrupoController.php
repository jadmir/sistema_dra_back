<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Permite personalizar la cantidad de registros por página (por defecto 10)
            $perPage = (int)$request->input('per_page', 10);

            $grupos = Grupo::activos()
                ->with('subsector')
                ->orderBy('codigo')
                ->where('estado', 1)
                ->paginate($perPage);

            if ($grupos->total() === 0) {
                return response()->json([
                    'message' => 'No hay grupos activos.',
                    'data'    => $grupos
                ], 200);
            }

            return response()->json($grupos, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener la lista de grupos.'
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
            'sub_sector_id.required' => 'El sub sector es obligatorio.',
            'sub_sector_id.exists'   => 'El sub sector indicado no existe.',
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.unique'        => 'El código ya existe.',
        ];

        $data = $request->validate([
            'sub_sector_id' => 'required|exists:sub_sectores,id',
            'codigo'      => 'required|string|max:100|unique:grupos,codigo',
            'descripcion' => 'nullable|string|max:200',
        ], $mensajes);

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }

            $data['usuario_id'] = $user->id;
            $data['estado']     = 1;

            $grupo = Grupo::create($data);

            return response()->json([
                'message' => 'Grupo creado correctamente',
                'data'    => $grupo
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo crear el grupo.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $grupo = Grupo::with('subgrupos.cultivos','subsector')
                ->where('estado', 1) // solo activos
                ->find($id);

            if (!$grupo) {
                return response()->json([
                    'message' => 'Grupo no encontrado o inactivo.'
                ], 404);
            }

            return response()->json($grupo, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener el grupo.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mensajes = [
            'sub_sector_id.required' => 'El sub sector es obligatorio.',
            'sub_sector_id.exists'   => 'El sub sector indicado no existe.',
        ];

        $data = $request->validate([
            'sub_sector_id' => 'required|exists:sub_sectores,id',
            'descripcion' => 'nullable|string|max:200',
        ], $mensajes);

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }

            $grupo = Grupo::find($id);
            if (!$grupo) {
                return response()->json(['message' => 'Grupo no encontrado.'], 404);
            }

            // Bloquear actualización si está inactivo
            if ((int)$grupo->estado === 0) {
                return response()->json([
                    'message' => 'El grupo está desactivado y no puede actualizarse.'
                ], 409);
            }

            $data['usuario_id'] = $user->id;

            $grupo->update($data);

            return response()->json([
                'message' => 'Grupo actualizado correctamente',
                'data'    => $grupo
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo actualizar el grupo.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $grupo = Grupo::find($id);
            if (!$grupo) {
                return response()->json(['message' => 'Grupo no encontrado.'], 404);
            }

            // Bloquear eliminación si está inactivo
            if ((int)$grupo->estado === 0) {
                return response()->json([
                    'message' => 'El grupo ya está desactivado.'
                ], 409);
            }

            // Verificar si el grupo tiene subgrupos asociados
            if ($grupo->subgrupos()->exists()) {
                return response()->json([
                    'message' => 'No se puede eliminar el grupo porque tiene subgrupos asociados.'
                ], 409);
            }

            // En lugar de eliminar, cambiar el estado a 0 (inactivo)
            $grupo->estado = 0;
            $grupo->save();

            return response()->json([
                'message' => 'Grupo desactivado correctamente.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al desactivar el grupo.'
            ], 500);
        }
    }

    //Buscar grupos  por código o descripción o usuario
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

        $q = Grupo::query()
                ->with('subsector','subgrupos.cultivos')
                ->where('estado', 1); // solo activos

            if ($term !== '') {
                $q->where(function ($query) use ($term) {
                    $query->where('codigo', 'LIKE', "%{$term}%")
                          ->orWhere('descripcion', 'LIKE', "%{$term}%");
                });
            }

            if (!empty($usuarioId)) {
                $q->where('usuario_id', $usuarioId);
            }

            $grupos = $q->orderBy('codigo')
                        ->paginate($perPage);

            if ($grupos->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron grupos con los criterios indicados.',
                    'data'    => $grupos
                ], 200);
            }

            return response()->json($grupos, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al buscar grupos.'
            ], 500);
        }
    }

}
