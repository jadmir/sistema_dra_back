<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Rol;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Permite personalizar la cantidad de registros por página (por defecto 10)
            $perPage = (int)$request->input('per_page', 10);

            $roles = Rol::with('permisos')
                ->paginate($perPage);

            return response()->json($roles, 200);
        } catch (\Throwable $e) {
            // Captura cualquier excepción y responde con un JSON amigable
            return response()->json([
                'message' => 'Error al obtener la lista de roles.',
            ], 500);
        }
    }

    /**
     * Búsqueda de roles
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $id = $request->input('id');

        $roles = Rol::with('permisos');

        // Si hay búsqueda por ID específico
        if ($id) {
            $roles->where('id', $id);
        }

        // Búsqueda general por nombre o descripción
        if ($query) {
            $roles->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('descripcion', 'LIKE', "%{$query}%");
            });
        }

        return response()->json($roles->paginate(10));
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
            'nombre.required'    => 'El nombre del rol es obligatorio.',
            'nombre.unique'      => 'El nombre del rol ya existe.',
            'descripcion.string' => 'La descripción debe ser texto.'
        ];

        $validated = $request->validate([
            'nombre'      => 'required|unique:roles,nombre',
            'descripcion' => 'nullable|string',
        ], $mensajes);

        try {
            $role = Rol::create($validated);

            return response()->json([
                'message' => 'Rol creado correctamente',
                'rol'    => $role->load('permisos')
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo crear el rol.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $rol = Rol::with('permisos')->find($id);

            if (!$rol) {
                return response()->json(['message' => 'Rol no encontrado.'], 404);
            }

            return response()->json([
                'message' => 'Rol obtenido correctamente',
                'rol'     => $rol
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener el rol.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rol $rol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $role = Rol::findOrFail($id);

            if(!$role){
                return response()->json([
                    'message' => 'Rol no encontrado'
                ], 404);
            }

            $mensajes = [
                'nombre.required'    => 'El nombre del rol es obligatorio.',
                'nombre.unique'      => 'El nombre del rol ya existe.',
                'descripcion.string' => 'La descripción debe ser texto.'
            ];

            $validated = $request->validate([
                'nombre'      => ['sometimes','required','string', Rule::unique('roles','nombre')->ignore($role->id)],
                'descripcion' => ['sometimes','nullable','string'],
            ], $mensajes);

            if (empty($validated)) {
                return response()->json(['message' => 'No se enviaron datos para actualizar.'], 422);
            }

            $role->update($validated);

            return response()->json([
                'message' => 'Rol actualizado correctamente',
                'rol'     => $role->refresh()->load('permisos')
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo actualizar el rol.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $role = Rol::findOrFail($id);

            if(!$role){
                return response()->json([
                    'message' => 'Rol no encontrado'
                ], 404);
            }

            if ($role->usuarios_count > 0) {
                return response()->json([
                    'message' => 'No se puede eliminar: el rol tiene usuarios asociados.'
                ], 409);
            }

            // Quitar permisos antes de eliminar (opcional pero limpio)
            $role->permisos()->detach();
            $role->delete();

            return response()->json([
                'message' => 'Rol eliminado correctamente'
            ], 200);
        } catch (\Throwable $te) {
            return response()->json([
                'message' => 'Error al eliminar el rol.'
            ], 500);
        }

    }

    public function asignarPermisos(Request $request, $id)
{
    $mensajes = [
        'permisos.required' => 'Debe proporcionar un array de IDs de permisos.',
        'permisos.array'    => 'El campo permisos debe ser un array.',
        'permisos.*.exists' => 'Uno o más IDs de permisos no existen.',
        'modo.in'           => 'El modo debe ser "sync" o "append".'
    ];

    // Quitar min:1 para permitir vacío y así poder limpiar todos
    $data = $request->validate([
        'permisos'   => ['required','array'],        // sin min:1
        'permisos.*' => ['integer','exists:permisos,id'],
        'modo'       => ['nullable','in:sync,append']
    ], $mensajes);

    try {
        $rol = Rol::with('permisos')->find($id);
        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado.'], 404);
        }

        $sincronizar = ($data['modo'] ?? 'sync') === 'sync';

        if ($sincronizar) {
            // Permite sync([]) para limpiar todos
            $rol->permisos()->sync($data['permisos']);
        } else {
            // Agrega nuevos sin eliminar existentes
            $existentes = $rol->permisos()->pluck('permisos.id')->toArray();
            $nuevos = array_diff($data['permisos'], $existentes);
            if (!empty($nuevos)) {
                $rol->permisos()->attach($nuevos);
            }
        }

        $rol->load('permisos');

        return response()->json([
            'message' => $sincronizar
                ? 'Permisos sincronizados correctamente.'
                : 'Permisos agregados correctamente.',
            'rol' => $rol
        ], 200);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Error al asignar permisos al rol.',
        ], 500);
    }
}

}
