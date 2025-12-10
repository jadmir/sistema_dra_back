<?php

namespace App\Http\Controllers;

use App\Models\AgriUnidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgriUnidadController extends Controller
{
    // Listar con paginación y búsqueda
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search  = $request->input('search');

        $query = AgriUnidad::query();

        if (!empty($search)) {
            $query->where('nombre', 'LIKE', "%{$search}%");
        }

        $unidades = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $unidades
        ]);
    }

    // Listar todas activas
    public function all()
    {
        try {
            $unidades = AgriUnidad::where('estado', 1)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre']);

            return response()->json($unidades);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar unidades',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // Crear
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $unidad = AgriUnidad::create([
            'nombre'     => $request->nombre,
            'estado'     => 1,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $unidad
        ]);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $unidad = AgriUnidad::findOrFail($id);

        $unidad->update([
            'nombre'     => $request->nombre,
            'estado'     => 1,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $unidad
        ]);
    }

    // Desactivar
    public function destroy($id)
    {
        $unidad = AgriUnidad::findOrFail($id);

        $unidad->update([
            'estado'     => 0,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Unidad desactivada correctamente.'
        ]);
    }
}
