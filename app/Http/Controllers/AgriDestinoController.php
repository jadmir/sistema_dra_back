<?php

namespace App\Http\Controllers;

use App\Models\AgriDestino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AgriDestinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            //Permite personalizar la cantidad de registros por página (por defecto 10)
            $perPage = (int) $request->input('per_page', 10);
            $destinos = AgriDestino::where('activo', true)
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            if($destinos->total() === 0){
                return response()->json([
                    'message' => 'No hay destinos activos',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Lista de destinos activos',
                'data' => $destinos
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener los destinos'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mensajes = [
            'nombre.required'    => 'El nombre es obligatorio.',
            'nombre.max'         => 'El nombre no debe exceder 150 caracteres.',
            'ubicacion.max'      => 'La ubicación no debe exceder 200 caracteres.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'estado.in'          => 'El estado debe ser ACTIVO o INACTIVO.',
        ];

        $validated = $request->validate([
            'nombre'     => 'required|string|max:150',
            'ubicacion'  => 'nullable|string|max:200',
            'descripcion'=> 'nullable|string',
            'estado'     => 'nullable|in:ACTIVO,INACTIVO', // si usas "activo" boolean, cámbialo por sometimes|boolean
            // usuario_id ya no viene del cliente
        ], $mensajes);

        try {
            $user = $request->user(); // requiere ruta protegida con auth
            if (!$user) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }

            $validated['usuario_id'] = $user->id;
            $validated['estado'] = $validated['estado'] ?? 'ACTIVO'; // opcional: por defecto ACTIVO

            $destino = AgriDestino::create($validated);

            return response()->json([
                'message' => 'Destino creado correctamente',
                'data'    => $destino
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo crear el destino.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $agriDestino = AgriDestino::find($id);

            if (!$agriDestino) {
                return response()->json(['message' => 'Destino no encontrado.'], 404);
            }

            // Soporta ambas convenciones: "activo" (bool) o "estado" (ACTIVO/INACTIVO)
            $attrs = $agriDestino->getAttributes();
            $inactivoPorActivo = array_key_exists('activo', $attrs) && (int)$agriDestino->activo === 0;
            $inactivoPorEstado = array_key_exists('estado', $attrs) && strtoupper((string)$agriDestino->estado) !== 'ACTIVO';

            if ($inactivoPorActivo || $inactivoPorEstado) {
                return response()->json(['message' => 'Destino no encontrado o inactivo.'], 404);
            }

            // Si tienes relación usuario:
            $agriDestino->loadMissing('usuario');

            return response()->json([
                'message' => 'Destino encontrado.',
                'data'    => $agriDestino
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al obtener el destino.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $destino = AgriDestino::find($id);

        if (!$destino) {
            return response()->json([
                'message' => 'Destino no encontrado.'
            ], 404);
        }

        $mensajes = [
            'nombre.required'    => 'El nombre es obligatorio.',
            'nombre.max'         => 'El nombre no debe exceder 150 caracteres.',
            'ubicacion.max'      => 'La ubicación no debe exceder 200 caracteres.',
            'descripcion.string' => 'La descripción debe ser texto.',
        ];

        $request->validate([
            'nombre' => 'sometimes|required|string|max:150',
            'ubicacion' => 'nullable|string|max:200',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $destino->fill($request->only(['nombre', 'ubicacion', 'descripcion']));
            $destino->save();

            return response()->json([
                'message' => 'Destino actualizado correctamente.',
                'data'    => $destino
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo actualizar el destino.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $destino = AgriDestino::find($id);

            if (!$destino) {
                return response()->json([
                    'message' => 'Destino no encontrado.'
                ], 404);
            }

            // Si usas "activo" boolean
            if (array_key_exists('activo', $destino->getAttributes())) {
                $destino->activo = false;
            }
            // Si usas "estado" string
            elseif (array_key_exists('estado', $destino->getAttributes())) {
                $destino->estado = 'INACTIVO';
            } else {
                return response()->json([
                    'message' => 'No se pudo desactivar el destino. Atributo de estado no encontrado.'
                ], 500);
            }

            $destino->save();

            return response()->json([
                'message' => 'Destino desactivado correctamente.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo desactivar el destino.'
            ], 500);
        }
    }

    // búsqueda de destinos mejorada
    public function search(Request $request)
    {
        try {
            // Paginación (por defecto 10)
            $perPage = (int) $request->input('per_page', 10);
            $perPage = max(1, min(100, $perPage));

            // Acepta ?q=, ?nombre= o ?query=
            $term = trim((string) ($request->input('q')
                ?? $request->input('nombre')
                ?? $request->input('query')
                ?? ''));

            $query = AgriDestino::where('activo', true);

            if ($term !== '') {
                $query->where(function ($q) use ($term) {
                    $q->where('nombre', 'like', "%{$term}%")
                      ->orWhere('ubicacion', 'like', "%{$term}%")
                      ->orWhere('descripcion', 'like', "%{$term}%");
                });
            }

            // Filtros opcionales específicos (si los envías)
            if ($request->filled('ubicacion')) {
                $query->where('ubicacion', 'like', '%' . trim($request->input('ubicacion')) . '%');
            }
            if ($request->filled('descripcion')) {
                $query->where('descripcion', 'like', '%' . trim($request->input('descripcion')) . '%');
            }

            $destinos = $query->orderBy('id', 'desc')->paginate($perPage);

            if ($destinos->total() === 0) {
                return response()->json([
                    'message' => 'No se encontraron destinos con los criterios dados.',
                    'data'    => []
                ], 200);
            }

            return response()->json([
                'message' => 'Resultados de la búsqueda',
                'data'    => $destinos
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al realizar la búsqueda.'
            ], 500);
        }
    }
}
