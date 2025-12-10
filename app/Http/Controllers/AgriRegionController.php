<?php

namespace App\Http\Controllers;

use App\Models\AgriRegion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgriRegionController extends Controller
{

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search  = $request->input('search');

        $query = AgriRegion::query();

        if (!empty($search)) {
            $query->where('nombre', 'LIKE', "%{$search}%");
        }

        $regiones = $query->orderBy('id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $regiones
        ]);
    }

    public function all()
    {
        try {
            $regiones = AgriRegion::where('estado', 1)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre']);

            return response()->json($regiones);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar regiones',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $region = AgriRegion::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $region
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $region = AgriRegion::create([
            'nombre'     => $request->nombre,
            'estado'     => 1,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $region
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $region = AgriRegion::findOrFail($id);

        $region->update([
            'nombre'     => $request->nombre,
            'estado'     => 1,
            'usuario_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'data'    => $region
        ]);
    }


    public function destroy($id)
    {
        $region = AgriRegion::findOrFail($id);

        $region->update([
            'estado'     => 0,
            'usuario_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Región desactivada correctamente.'
        ]);
    }
}
