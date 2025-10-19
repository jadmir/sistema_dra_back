<?php

namespace App\Http\Controllers;

use App\Models\SubGrupo;
use Illuminate\Http\Request;

class SubGrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Permite personalizar la cantidad de registros por página (por defecto 10)
            $perPage = (int)$request->input('per_page', 10);

            $subGrupos = SubGrupo::with('grupo')
                ->orderBy('codigo')
                ->where('estado', 1)
                ->paginate($perPage);

            if ($subGrupos->total() === 0) {
                return response()->json([
                    'message' => 'No hay subgrupos disponibles.',
                    'data'    => $subGrupos
                ], 200);
            }

            return response()->json($subGrupos, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener la lista de subgrupos.'
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
            'grupo_id.required' => 'El grupo es obligatorio.',
            'grupo_id.exists'   => 'El grupo indicado no existe.',
            'codigo.required'   => 'El código es obligatorio.',
            'codigo.unique'     => 'El código ya existe.',
        ];

        $data = $request->validate([
            'grupo_id'    => 'required|exists:grupos,id',
            'codigo'      => 'required|string|max:100|unique:sub_grupos,codigo',
            'descripcion' => 'nullable|string|max:200',
        ], $mensajes);

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }

            $data['usuario_id'] = $user->id;
            $data['estado']     = 1;


            $subGrupo = SubGrupo::create($data);

            return response()->json([
                'message' => 'Subgrupo creado correctamente',
                'data'    => $subGrupo
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo crear el subgrupo.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $subGrupo = SubGrupo::with('cultivos','grupo.subsector')
                ->where('estado', 1) // solo activos
                ->find($id);

            if (!$subGrupo) {
                return response()->json([
                    'message' => 'Subgrupo no encontrado.'
                ], 404);
            }

            return response()->json($subGrupo, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener el subgrupo.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubGrupo $subGrupo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mensajes = [
            'grupo_id.required' => 'El grupo es obligatorio.',
            'grupo_id.exists'   => 'El grupo indicado no existe.',
        ];

        $data = $request->validate([
            'grupo_id'    => 'required|exists:grupos,id',
            'descripcion' => 'nullable|string|max:200',
        ], $mensajes);

        try {

            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }

            $subGrupo = SubGrupo::find($id);
            if (!$subGrupo) {
                return response()->json(['message' => 'Subgrupo no encontrado.'], 404);
            }

            // Bloquear actualización si está inactivo
            if ((int)$subGrupo->estado === 0) {
                return response()->json([
                    'message' => 'El subgrupo está desactivado y no puede actualizarse.'
                ], 409);
            }

            $data['usuario_id'] = $user->id;
            $subGrupo->update($data);

            return response()->json([
                'message' => 'Subgrupo actualizado correctamente',
                'data'    => $subGrupo
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo actualizar el subgrupo.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $subGrupo = SubGrupo::find($id);
            if (!$subGrupo) {
                return response()->json(['message' => 'Subgrupo no encontrado.'], 404);
            }

            // Bloquear eliminación si está inactivo
            if ((int)$subGrupo->estado === 0) {
                return response()->json([
                    'message' => 'El grupo ya está desactivado.'
                ], 409);
            }

            // Verificar si el grupo tiene subgrupos asociados
            if ($subGrupo->subgrupos()->exists()) {
                return response()->json([
                    'message' => 'No se puede eliminar el grupo porque tiene subgrupos asociados.'
                ], 409);
            }

            // Aquí podrías implementar una lógica de "soft delete" si es necesario
            $subGrupo->estado = 0; // Marcar como inactivo
            $subGrupo->save();

            return response()->json([
                'message' => 'Subgrupo eliminado correctamente.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo eliminar el subgrupo.'
            ], 500);
        }
    }

    //buscar subgrupos por codigo y descripcion
    public function search(Request $request)
    {
        try {
            $term     = trim((string) ($request->query('search') ?? $request->query('q') ?? ''));
            $usuarioId= $request->query('usuario_id');
            $perPage  = max(1, min(100, (int) $request->query('per_page', 10)));

            if ($term === '' && empty($usuarioId)) {
                return response()->json([
                    'message' => 'Debes enviar al menos un parámetro de búsqueda (search o usuario_id).',
                    'data'    => []
                ], 422);
            }

            $q = SubGrupo::query()
                ->with(['grupo.subsector', 'cultivos']) // FIX: relación correcta
                ->where('estado', 1);

            if ($term !== '') {
                $q->where(function ($query) use ($term) {
                    $query->where('codigo', 'like', "%{$term}%")
                          ->orWhere('descripcion', 'like', "%{$term}%");
                });
            }

            if (!empty($usuarioId)) {
                $q->where('usuario_id', (int) $usuarioId);
            }

            $subGrupos = $q->orderBy('codigo')->paginate($perPage);

            if ($subGrupos->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron subgrupos con los criterios indicados.',
                    'data'    => $subGrupos
                ], 200);
            }

            return response()->json($subGrupos, 200);
        } catch (\Throwable $th) {
            // \Log::error('SubGrupo search error', ['msg'=>$th->getMessage()]); // opcional
            return response()->json([
                'message' => 'Error al buscar subgrupos.'
            ], 500);
        }
    }
}
