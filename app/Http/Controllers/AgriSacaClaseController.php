<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgriSacaClase;
use Illuminate\Support\Facades\Auth;

class AgriSacaClaseController extends Controller
{
  
    // Listado
    public function index()
    {
        $clases = AgriSacaClase::where('estado', 'activo')->get();
        return response()->json($clases);
    }

    // Crear
    public function store(Request $request)
    {
    $request->validate([
        'nombre' => 'required|string|max:150',
        'descripcion' => 'nullable|string',
        'estado' => 'nullable|string|in:activo,inactivo'
    ]);

    $usuarioId = $request->get('jwt_user_id');
    if (!$usuarioId) {
        return response()->json(['message' => 'Usuario no autenticado'], 401);
    }

    $clase = new AgriSacaClase();
    $clase->nombre = $request->nombre;
    $clase->descripcion = $request->descripcion ?? null;
    $clase->estado = $request->estado ?? 'activo';
    $clase->usuario_id = $usuarioId;

    $clase->save();

    return response()->json([
        'message' => 'Clase creada correctamente',
        'data' => $clase
    ], 201);
    }

    // Mostrar
    public function show($id)
    {
        $clase = AgriSacaClase::findOrFail($id);
        return response()->json($clase);
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $clase = AgriSacaClase::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string|in:activo,inactivo'
        ]);

        $clase->update($request->only(['nombre', 'descripcion', 'estado']));

        return response()->json($clase);
    }

    // Eliminar
    public function destroy($id)
    {
        $clase = AgriSacaClase::findOrFail($id);
        $clase->update(['estado' => 'inactivo']);
        $clase->delete();

        return response()->json(['message' => 'Registro eliminado correctamente']);
    }

    // Búsqueda avanzada
    public function search(Request $request)
    {
        $query = $request->input('query', '');

        $clases = AgriSacaClase::where('estado', 'activo')
            ->where(function($q) use ($query) {
                $q->where('nombre', 'like', "%$query%")
                  ->orWhere('descripcion', 'like', "%$query%");
            })
            ->get();

        return response()->json($clases);
    }
}