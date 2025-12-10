<?php

namespace App\Http\Controllers;

use App\Models\AgriDistrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgriDistritoController extends Controller
{
    // Listar distritos con búsqueda y paginación
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search  = $request->input('search');

        $query = AgriDistrito::with('provincia');

        if (!empty($search)) {
            $query->where('nombre', 'LIKE', "%{$search}%");
        }

        $distritos = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $distritos
        ]);
    }

    // Listar todos los distritos activos
    public function all()
    {
        try {
            $distritos = AgriDistrito::where('estado', 1)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre']);

            return response()->json($distritos);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar distritos',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // Crear distrito
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'provincia_id' => 'required|exists:agri_provincias,id',
        ]);

        $distrito = AgriDistrito::create([
            'nombre'       => $request->nombre,
            'provincia_id' => $request->provincia_id,
            'estado'       => 1,
            'usuario_id'   => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $distrito
        ]);
    }

    // Actualizar distrito
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'provincia_id' => 'required|exists:agri_provincias,id',
        ]);

        $distrito = AgriDistrito::findOrFail($id);

        $distrito->update([
            'nombre'       => $request->nombre,
            'provincia_id' => $request->provincia_id,
            'estado'       => 1,
            'usuario_id'   => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $distrito
        ]);
    }

    // Desactivar distrito
    public function destroy($id)
    {
        $distrito = AgriDistrito::findOrFail($id);

        $distrito->update([
            'estado'     => 0,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Distrito desactivado correctamente.'
        ]);
    }
}
