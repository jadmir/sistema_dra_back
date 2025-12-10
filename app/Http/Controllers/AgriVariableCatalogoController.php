<?php

namespace App\Http\Controllers;

use App\Models\AgriVariableCatalogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgriVariableCatalogoController extends Controller
{
    // Listar con paginación y búsqueda
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search  = $request->input('search');

        $query = AgriVariableCatalogo::with('unidad');

        if (!empty($search)) {
            $query->where('nombre', 'LIKE', "%{$search}%");
        }

        $variables = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $variables
        ]);
    }

    // Listar todas activas
    public function all()
    {
        try {
            $variables = AgriVariableCatalogo::where('estado', 1)
                ->with('unidad')
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre', 'unidad_id']);

            return response()->json($variables);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar variables',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // Crear
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'unidad_id' => 'required|exists:agri_unidades,id',
        ]);

        $variable = AgriVariableCatalogo::create([
            'nombre'     => $request->nombre,
            'unidad_id'  => $request->unidad_id,
            'estado'     => 1,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $variable
        ]);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'unidad_id' => 'required|exists:agri_unidades,id',
        ]);

        $variable = AgriVariableCatalogo::findOrFail($id);

        $variable->update([
            'nombre'     => $request->nombre,
            'unidad_id'  => $request->unidad_id,
            'estado'     => 1,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $variable
        ]);
    }

    // Desactivar
    public function destroy($id)
    {
        $variable = AgriVariableCatalogo::findOrFail($id);

        $variable->update([
            'estado'     => 0,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Variable desactivada correctamente.'
        ]);
    }
}
