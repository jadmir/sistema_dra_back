<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgriAnimal;
use Illuminate\Support\Facades\Auth;

class AgriAnimalController extends Controller
{
    public function index() {
        return response()->json(AgriAnimal::where('estado','activo')->get());
    }

    public function store(Request $request) {
        $request->validate([
            'codigo'=>'required|string|max:50',
            'variedad_id'=>'required|integer',
            'edad'=>'required|integer',
            'peso'=>'required|numeric',
            'estado'=>'nullable|string|in:activo,inactivo'
        ]);

        $usuario = Auth::user();
        if(!$usuario) return response()->json(['message'=>'Usuario no autenticado'],401);

        $animal = AgriAnimal::create([
            'codigo'=>$request->codigo,
            'variedad_id'=>$request->variedad_id,
            'edad'=>$request->edad,
            'peso'=>$request->peso,
            'estado'=>$request->estado ?? 'activo',
            'usuario_id'=>$usuario->id
        ]);

        return response()->json(['message'=>'Animal creado correctamente','data'=>$animal],201);
    }

    public function show($id) {
        return response()->json(AgriAnimal::find($id));
    }

    public function update(Request $request, $id) {
        $animal = AgriAnimal::find($id);

        $request->validate([
            'codigo'=>'required|string|max:50',
            'variedad_id'=>'required|integer',
            'edad'=>'required|integer',
            'peso'=>'required|numeric',
            'estado'=>'required|string|in:activo,inactivo'
        ]);

        $animal->update($request->only(['codigo','variedad_id','edad','peso','estado']));
        return response()->json($animal);
    }

    public function destroy($id) {
        $animal = AgriAnimal::find($id);
        $animal->estado='inactivo';
        $animal->save();
        $animal->delete();
        return response()->json(['message'=>'Agri Animal eliminado correctamente']);
    }

    public function search(Request $request) {
        $query = $request->input('query','');
        $animales = AgriAnimal::where('estado','activo')
            ->where(function($q) use ($query){
                $q->where('codigo','like',"%$query%");
            })->get();

        return response()->json($animales);
    }
}
