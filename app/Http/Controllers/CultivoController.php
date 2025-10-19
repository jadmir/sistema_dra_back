<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use Illuminate\Http\Request;

class CultivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Permite personalizar la cantidad de registros por página (por defecto 10)
            $perPage = (int)$request->input('per_page', 10);

            $cultivos = Cultivo::with('subgrupo.grupo.subsector')
                ->orderBy('codigo')
                ->where('estado', 1)
                ->paginate($perPage);

            if ($cultivos->total() === 0){
                return response()->json([
                    'message' => 'No hay cultivos disponibles.',
                    'data'    => $cultivos
                ], 200);
            }

            return response()->json($cultivos, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener la lista de cultivos.'
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
            'sub_grupo_id.required' => 'El sub grupo es obligatorio.',
            'sub_grupo_id.exists'   => 'El sub grupo indicado no existe.',
            'codigo.required'       => 'El código es obligatorio.',
            'codigo.unique'         => 'El código ya existe.',
        ];

        $data = $request->validate([
            'sub_grupo_id' => 'required|exists:sub_grupos,id',
            'codigo'       => 'required|string|max:100|unique:cultivos,codigo',
            'descripcion'  => 'nullable|string|max:200',
        ], $mensajes);

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }

            $data['usuario_id'] = $user->id;
            $data['estado']     = 1;

            $cultivo = Cultivo::create($data);

            return response()->json([
                'message' => 'Cultivo creado correctamente',
                'data'    => $cultivo
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo crear el cultivo.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $cultivo = Cultivo::with('subgrupo.grupo.subsector')
                ->where('estado', 1) // solo activos
                ->find($id);

            if (!$cultivo) {
                return response()->json([
                    'message' => 'Cultivo no encontrado.'
                ], 404);
            }

            return response()->json($cultivo, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener el cultivo.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cultivo $cultivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mensajes = [
            'sub_grupo_id.required' => 'El sub grupo es obligatorio.',
            'sub_grupo_id.exists'   => 'El sub grupo indicado no existe.',
        ];

        $data = $request->validate([
            'sub_grupo_id' => 'required|exists:sub_grupos,id',
            'descripcion'  => 'nullable|string|max:200',
        ], $mensajes);

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }

            $cultivo = Cultivo::find($id);
            if (!$cultivo) {
                return response()->json(['message' => 'Cultivo no encontrado.'], 404);
            }

            // Bloquear actualización si está inactivo
            if ((int)$cultivo->estado === 0) {
                return response()->json([
                    'message' => 'El cultivo está desactivado y no puede actualizarse.'
                ], 409);
            }

            $data['usuario_id'] = $user->id;
            $cultivo->update($data);

            return response()->json([
                'message' => 'Cultivo actualizado correctamente.',
                'data'    => $cultivo
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener el cultivo.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $cultivo = Cultivo::find($id);
            if (!$cultivo) {
                return response()->json(['message' => 'Cultivo no encontrado.'], 404);
            }

            if ((int)$cultivo->estado === 0) {
                return response()->json([
                    'message' => 'El cultivo ya está desactivado.'
                ], 200);
            }

            // Si necesitas bloquear por dependencias, reemplaza por la relación correcta, ej:
            // if ($cultivo->lotes()->exists()) { return response()->json(['message'=>'No se puede eliminar: tiene lotes asociados.'], 409); }

            $cultivo->estado = 0; // baja lógica
            $cultivo->save();

            return response()->json([
                'message' => 'Cultivo desactivado correctamente.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al eliminar el cultivo.'
            ], 500);
        }
    }

    //buscar cultivos por codigo y descripcion
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

            $q = Cultivo::query()
                ->with(['subgrupo.grupo.subsector']) // FIX: relación correcta
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

            $cultivos = $q->orderBy('codigo')->paginate($perPage);

            if ($cultivos->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron cultivos con los criterios indicados.',
                    'data'    => $cultivos
                ], 200);
            }

            return response()->json($cultivos, 200);
        } catch (\Throwable $th) {
            // \Log::error('Cultivo search error', ['msg'=>$th->getMessage()]); // opcional
            return response()->json([
                'message' => 'Error al buscar cultivos.'
            ], 500);
        }
    }
}
