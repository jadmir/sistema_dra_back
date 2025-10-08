<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgriVariedad;

class AgriVariedadController extends Controller
{
    public function index() {
        return response()->json(AgriVariedad::where('estado','activo')->get());
    }

    public function store(Request $request) {
        $request->validate([
            'nombre'=>'required|string|max:150',
            'descripcion'=>'nullable|string',
            'estado'=>'nullable|string|in:activo,inactivo'
        ]);

        $variedad = AgriVariedad::create([
            'nombre'=>$request->nombre,
            'descripcion'=>$request->descripcion ?? null,
            'estado'=>$request->estado ?? 'activo'
        ]);

        return response()->json(['message'=>'Variedad creada correctamente','data'=>$variedad],201);
    }

    public function show($id) {
        return response()->json(AgriVariedad::find($id));
    }

    public function update(Request $request, $id) {
        $variedad = AgriVariedad::find($id);

        $request->validate([
            'nombre'=>'required|string|max:150',
            'descripcion'=>'nullable|string',
            'estado'=>'required|string|in:activo,inactivo'
        ]);

        $variedad->update($request->only(['nombre','descripcion','estado']));
        return response()->json($variedad);
    }

    public function destroy($id) {
        $variedad = AgriVariedad::find($id);
        $variedad->estado='inactivo';
        $variedad->save();
        $variedad->delete();
        return response()->json(['message'=>'Variedad eliminada correctamente']);
    }

    public function search(Request $request) {
        $query = $request->input('query','');
        $variedades = AgriVariedad::where('estado','activo')
            ->where('nombre','like',"%$query%")
            ->get();
        return response()->json($variedades);
    }
}
