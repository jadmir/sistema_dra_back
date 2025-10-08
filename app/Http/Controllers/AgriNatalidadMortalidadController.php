<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AgriNatalidadMortalidad;
use Illuminate\Support\Facades\Auth;

class AgriNatalidadMortalidadController extends Controller
{
    // Listado general
    public function index()
    {
        $registros = AgriNatalidadMortalidad::where('estado', true)->get();
        return response()->json([
            'success' => true,
            'data' => $registros
        ]);
    }

    // Crear nuevo registro
    public function store(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|integer',
            'tipo' => 'required|string|in:natalidad,mortalidad',
            'concepto' => 'required|string|max:100',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $usuario = Auth::user();
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $registro = AgriNatalidadMortalidad::create([
            'animal_id' => $request->animal_id,
            'tipo' => $request->tipo,
            'concepto' => $request->concepto,
            'fecha' => $request->fecha,
            'observaciones' => $request->observaciones ?? null,
            'usuario_id' => $usuario->id,
            'estado' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registro creado correctamente',
            'data' => $registro
        ], 201);
    }

    // Mostrar un registro
    public function show($id)
    {
        $registro = AgriNatalidadMortalidad::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $registro
        ]);
    }

    // Actualizar registro
    public function update(Request $request, $id)
    {
        $registro = AgriNatalidadMortalidad::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $request->validate([
            'animal_id' => 'required|integer',
            'tipo' => 'required|string|in:natalidad,mortalidad',
            'concepto' => 'required|string|max:100',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
            'estado' => 'required|boolean',
        ]);

        $registro->update($request->only([
            'animal_id', 'tipo', 'concepto', 'fecha', 'observaciones', 'estado'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Registro actualizado correctamente',
            'data' => $registro
        ]);
    }

    // Eliminación lógica
    public function destroy($id)
    {
        $registro = AgriNatalidadMortalidad::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $registro->estado = false;
        $registro->save();
        $registro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro eliminado correctamente'
        ]);
    }

    // Búsqueda
    public function search(Request $request)
    {
        $query = $request->input('query', '');

        $registros = AgriNatalidadMortalidad::where('estado', true)
            ->where(function ($q) use ($query) {
                $q->where('concepto', 'like', "%$query%")
                  ->orWhere('observaciones', 'like', "%$query%");
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $registros
        ]);
    }
}
