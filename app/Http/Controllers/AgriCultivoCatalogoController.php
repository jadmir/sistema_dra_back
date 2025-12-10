<?php

namespace App\Http\Controllers;

use App\Models\AgriCultivoCatalogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgriCultivoCatalogoController extends Controller
{
    // Listar con paginación y búsqueda
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search  = $request->input('search');

        $query = AgriCultivoCatalogo::query();

        if (!empty($search)) {
            $query->where('nombre', 'LIKE', "%{$search}%");
        }

        $catalogos = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $catalogos
        ]);
    }

    // Listar todos activos
    public function all()
    {
        try {
            $catalogos = AgriCultivoCatalogo::where('estado', 1)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre']);

            return response()->json($catalogos);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar cultivos',
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

        $catalogo = AgriCultivoCatalogo::create([
            'nombre'     => $request->nombre,
            'estado'     => 1,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $catalogo
        ]);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $catalogo = AgriCultivoCatalogo::findOrFail($id);

        $catalogo->update([
            'nombre'     => $request->nombre,
            'estado'     => 1,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $catalogo
        ]);
    }

    // Desactivar
    public function destroy($id)
    {
        $catalogo = AgriCultivoCatalogo::findOrFail($id);

        $catalogo->update([
            'estado'     => 0,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cultivo desactivado correctamente.'
        ]);
    }
}
