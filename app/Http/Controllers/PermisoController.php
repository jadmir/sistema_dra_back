<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermisoRequest;
use App\Http\Requests\UpdatePermisoRequest;
use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Permite personalizar la cantidad de registros por página (por defecto 10)
            $perPage = (int)$request->input('per_page', 10);

            $permisos = Permiso::paginate($perPage);

            return response()->json($permisos, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener la lista de permisos.'
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
            'nombre.required'    => 'El nombre del permiso es obligatorio.',
            'nombre.unique'      => 'El nombre del permiso ya existe.',
            'descripcion.string' => 'La descripción debe ser texto.'
        ];

        $validated = $request->validate([
            'nombre'      => 'required|unique:permisos,nombre',
            'descripcion' => 'nullable|string',
        ], $mensajes);

        try {
            $validated['nombre'] = trim($validated['nombre']);

            $permiso = Permiso::create($validated);

            return response()->json([
                'message' => 'Permiso creado correctamente',
                'permiso' => $permiso
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al crear el permiso.',
                'error'   => $th->getMessage() // TEMP: quitar en producción
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $permiso = Permiso::find($id);
            if (!$permiso) {
                return response()->json([
                    'message' => 'Permiso no encontrado.'
                ], 404);
            }

            return response()->json([
                'message' => 'Permiso obtenido correctamente',
                'permiso' => $permiso
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener el permiso.',
                'error'   => $th->getMessage() // TEMP: quitar en producción
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permiso $permiso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $permiso = Permiso::find($id);
            if (!$permiso) {
                return response()->json([
                    'message' => 'Permiso no encontrado.'
                ], 404);
            }

            $mensajes = [
                'nombre.required'    => 'El nombre del permiso es obligatorio.',
                'nombre.unique'      => 'El nombre del permiso ya existe.',
                'descripcion.string' => 'La descripción debe ser texto.'
            ];

            $validated = $request->validate([
                'nombre'      => ['sometimes','required',"unique:permisos,nombre,{$permiso->id}"],
                'descripcion' => ['sometimes','nullable','string'],
            ], $mensajes);

            if (empty($validated)) {
                return response()->json(['message' => 'No se enviaron datos para actualizar.'], 422);
            }

            // Normalización sencilla
            if (isset($validated['nombre'])) {
                $validated['nombre'] = trim($validated['nombre']);
            }
            if (isset($validated['descripcion'])) {
                $validated['descripcion'] = trim($validated['descripcion']);
            }

            $permiso->fill($validated);

            if (!$permiso->isDirty()) {
                return response()->json(['message' => 'No hay cambios para aplicar.'], 422);
            }

            $permiso->save();

            return response()->json([
                'message' => 'permiso actualizado correctamente',
                'permiso' => $permiso
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al actualizar el permiso.',
                'error'   => $e->getMessage() // TEMP: quitar en producción
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $permiso = Permiso::find($id);
            if (!$permiso) {
                return response()->json([
                    'message' => 'Permiso no encontrado.'
                ], 404);
            }

            $permiso->delete();

            return response()->json([
                'message' => 'Permiso eliminado correctamente'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al eliminar el permiso.',
                'error'   => $e->getMessage() // TEMP: quitar en producción
            ], 500);
        }
    }
}
