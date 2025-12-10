<?php

namespace App\Http\Controllers;

use App\Models\AgriProvincia;
use App\Models\AgriRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgriProvinciaController extends Controller
{
    // LISTAR PROVINCIAS CON BÚSQUEDA Y PAGINACIÓN
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search  = $request->input('search');

        $query = AgriProvincia::with('region');

        if (!empty($search)) {
            $query->where('nombre', 'LIKE', "%{$search}%");
        }

        $provincias = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $provincias
        ]);
    }

    // LISTAR TODAS LAS PROVINCIAS ACTIVAS
    public function all()
    {
        try {
            $provincias = AgriProvincia::where('estado', 1)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre', 'region_id']);

            return response()->json($provincias);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar provincias',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'region_id' => 'required|exists:agri_regiones,id',
        ]);

        $provincia = AgriProvincia::create([
            'nombre'     => $request->nombre,
            'region_id'  => $request->region_id,
            'estado'     => 1,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $provincia
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'region_id' => 'required|exists:agri_regiones,id',
        ]);

        $provincia = AgriProvincia::findOrFail($id);

        $provincia->update([
            'nombre'     => $request->nombre,
            'region_id'  => $request->region_id,
            'estado'     => 1,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $provincia
        ]);
    }

    public function destroy($id)
    {
        $provincia = AgriProvincia::findOrFail($id);

        $provincia->update([
            'estado'     => 0,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Provincia desactivada correctamente.'
        ]);
    }
}
