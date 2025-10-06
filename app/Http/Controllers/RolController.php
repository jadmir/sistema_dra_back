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

        $role = Rol::created($validated);
        return response()->json([
            'message' => 'rol creado correctamente',
            'role' => $role
        ], 201);
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

    //Asignacion de permisos
    public function asignarPermisos(Request $request, $id)
    {
        $mensajes = [
            'permisos.required' => 'Debe proporcionar un array de IDs de permisos.',
            'permisos.array'    => 'El campo permisos debe ser un array.',
            'permisos.*.exists' => 'Uno o más IDs de permisos no existen.',
            'modo.in'           => 'El modo debe ser "sincronizar" o "agregar".'
        ];

        $data = $request->validate([
            'permisos'   => ['required','array','min:1'],
            'permisos.*' => ['integer','exists:permisos,id'],
            'modo'       => ['nullable','in:sync,append']
        ], $mensajes);

        try {
            $rol = Rol::with('permisos')->find($id);
            if (!$rol) {
                return response()->json([
                    'message' => 'Rol no encontrado.'
                ], 404);
            }

            $sincronizar = ($data['modo'] ?? 'sync') === 'sync';

            if ($sincronizar) {
                // Sincroniza: elimina permisos no incluidos y agrega los nuevos
                $rol->permisos()->sync($data['permisos']);
            } else {
                // Agrega: mantiene los existentes y añade los nuevos sin duplicados
                $existentes = $rol->permisos()->pluck('permisos.id')->toArray();
                $nuevos = array_diff($data['permisos'], $existentes);
                if ($nuevos) {
                    $rol->permisos()->attach($nuevos);
                }
            }

            return response()->json([
                'message' => $sincronizar
                    ? 'Permisos sincronizados correctamente.'
                    : 'Permisos agregados correctamente.',
                'rol' => $rol->load('permisos')
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al asignar permisos al rol.',
                'error'   => $e->getMessage() // TEMP: quitar en producción
            ], 500);
        }
    }

}
