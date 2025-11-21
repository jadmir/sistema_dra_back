<?php

namespace App\Http\Controllers;

use App\Models\AgriRegistroPecuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/* Models */
use App\Models\LecheFresca;
use App\Models\AgriProductoLeche;
use App\Models\SacaReproduccion;
use App\Models\SacaVacunoDescarte;
use App\Models\AgriNatalidad;
use App\Models\AgriMortalidad;
use App\Models\InformeTecnico;
use App\Models\AgriAnimales;
use App\Models\AnimalTotal;
use App\Models\AgriSacaTotal;

class AgriRegistroPecuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = AgriRegistroPecuario::with([
            'animales.variedad',
            'animalTotal',
            'productosLeche.destino',
            'lecheFresca',
            'sacaReproduccion.variedad',
            'sacaVacunoDescarte.variedad',
            'sacaTotal',
            'natalidad.natalidadMortalidad',
            'mortalidad.variedad',
            'informeTecnico'
        ]);

        $registros = $query->orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $registros->items(),
            'current_page' => $registros->currentPage(),
            'last_page' => $registros->lastPage(),
            'per_page' => $registros->perPage(),
            'total' => $registros->total(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            //Validación 
            $validated = $request->validate([
                'codigo_establo' => 'nullable|string|max:200',
                'ubigeo' => 'nullable|string|max:100',
                'mes_de_referencia' => 'required|string|max:250',
                'anio' => 'required|digits:4',
                'region' => 'required|string|max:100',
                'provincia' => 'required|string|max:100',
                'distrito' => 'required|string|max:100',
                'nombre_establo' => 'required|string|max:250',
                'producto_razon_social' => 'required|string|max:250',
                'direccion' => 'nullable|string|max:100',
                'ruc' => 'nullable|string|max:100',
            ]);

            $registro = AgriRegistroPecuario::create($validated);

            // Registrar animales
            $totalAnimales = 0;
            if ($request->filled('animales')) {
                foreach ($request->animales as $a) {
                    if (!empty($a['variedad_id']) && !empty($a['total'])) {
                        AgriAnimales::create([
                            'registro_pecuario_id' => $registro->id,
                            'variedad_id' => $a['variedad_id'],
                            'total' => $a['total'],
                            'estado' => true,
                            'usuario_id' => $request->usuario_id ?? 1,
                        ]);
                        $totalAnimales += (int)$a['total'];
                    }
                }

                if ($totalAnimales > 0) {
                    AnimalTotal::create([
                        'registro_pecuario_id' => $registro->id,
                        'total_animal' => $totalAnimales,
                    ]);
                }
            }

            // Registrar lecheFresca y total
            $totalLeche = 0;
            $lecheFresca = LecheFresca::create([
                'registro_pecuario_id' => $registro->id,
                'total_leche' => 0,
            ]);

            if ($request->filled('producto_leches')) {
                foreach ($request->producto_leches as $p) {

                    if (!isset($p['cantidad']) && !isset($p['precio'])) {
                        throw new \Exception('Debe proporcionar cantidad o precio en producto_leches');
                    }

                    AgriProductoLeche::create([
                        'registro_pecuario_id' => $registro->id,
                        'agri_destinos_id' => $p['agri_destinos_id'],
                        'leche_fresca_id' => $lecheFresca->id,
                        'cantidad' => $p['cantidad'] ?? null,
                        'precio' => $p['precio'] ?? null,
                        'usuario_id' => $request->usuario_id ?? 1,
                    ]);

                    $totalLeche += (float)($p['cantidad'] ?? 0);
                }

                $lecheFresca->update(['total_leche' => $totalLeche]);
            }

            // Registrar saca_reproduccion
            if ($request->filled('saca_reproduccion')) {
                foreach ($request->saca_reproduccion as $sr) {
                    if (!empty($sr['id_agri_variedad_animal'])) {
                        if (!isset($sr['saca_unidad']) && !isset($sr['precio_venta'])) {
                            throw new \Exception('Debe proporcionar saca_unidad o precio_venta en saca_reproduccion');
                        }

                        SacaReproduccion::create([
                            'id_agri_registro_pecuario' => $registro->id,
                            'id_agri_variedad_animal' => $sr['id_agri_variedad_animal'],
                            'saca_unidad' => $sr['saca_unidad'] ?? null,
                            'precio_venta' => $sr['precio_venta'] ?? null,
                            'usuario_id' => $request->usuario_id ?? 1,
                        ]);
                    }
                }
            }

            // Registrar saca_vacuno_descarte
            if ($request->filled('saca_vacuno_descarte')) {
                foreach ($request->saca_vacuno_descarte as $sd) {
                    if (!empty($sd['id_agri_variedad_animal'])) {
                        if (!isset($sd['saca_unidad']) && !isset($sd['precio_venta'])) {
                            throw new \Exception('Debe proporcionar saca_unidad o precio_venta en saca_vacuno_descarte');
                        }

                        SacaVacunoDescarte::create([
                            'id_agri_registro_pecuario' => $registro->id,
                            'id_agri_variedad_animal' => $sd['id_agri_variedad_animal'],
                            'saca_unidad' => $sd['saca_unidad'] ?? null,
                            'precio_venta' => $sd['precio_venta'] ?? null,
                            'peso_promedio_vivo' => $sd['peso_promedio_vivo'] ?? null,
                            'usuario_id' => $request->usuario_id ?? 1,
                        ]);
                    }
                }
            }

            // Registrar total de saca
            $totalSaca = collect($request->input('saca_reproduccion', []))->sum('saca_unidad') +
                        collect($request->input('saca_vacuno_descarte', []))->sum('saca_unidad');

            if ($totalSaca > 0) {
                AgriSacaTotal::create([
                    'id_agri_registro_pecuario' => $registro->id,
                    'total_leche' => $totalSaca,
                ]);
            }

            // Registrar natalidad
            if ($request->filled('natalidad')) {
                foreach ($request->natalidad as $n) {
                    if (!empty($n['natalidad_mortalidad_id'])) {
                        AgriNatalidad::create([
                            'id_agri_registro_pecuario' => $registro->id,
                            'natalidad_mortalidad_id' => $n['natalidad_mortalidad_id'],
                            'cantidad' => $n['cantidad'] ?? null,
                        ]);
                    }
                }
            }

            //Registrar mortalidad
            if ($request->filled('mortalidad')) {
                foreach ($request->mortalidad as $m) {
                    if (!empty($m['id_agri_variedad_animal'])) {
                        AgriMortalidad::create([
                            'id_agri_registro_pecuario' => $registro->id,
                            'id_agri_variedad_animal' => $m['id_agri_variedad_animal'],
                            'cantidad' => $m['cantidad'] ?? null,
                        ]);
                    }
                }
            }

            // Registrar inform técnico
            if ($request->filled('informe_tecnico')) {
                $info = $request->informe_tecnico;
                InformeTecnico::create([
                    'id_agri_registro_pecuario' => $registro->id,
                    'informante' => $info['informante'],
                    'email' => $info['email'] ?? null,
                    'telefono' => $info['telefono'] ?? null,
                    'cargo' => $info['cargo'],
                    'tecnico' => $info['tecnico'],
                    'observaciones' => $info['observaciones'] ?? null,
                    'fecha' => $info['fecha'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Registro pecuario guardado correctamente',
                'registro_pecuario_id' => $registro->id
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrió un error al registrar los datos',
                'detalles' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $registro = AgriRegistroPecuario::with([
                'animales.variedad',
                'animalTotal',
                'productosLeche.destino',
                'lecheFresca',
                'sacaReproduccion.variedad',
                'sacaVacunoDescarte.variedad',
                'sacaTotal',
                'natalidad.natalidadMortalidad',
                'mortalidad.variedad',
                'informeTecnico'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Registro pecuario encontrado.',
                'data' => $registro
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El registro pecuario no existe.'
                ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el registro pecuario.'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $registro = AgriRegistroPecuario::findOrFail($id);
            $usuarioId = auth::id();

            if ($registro->created_at->diffInMonths(now()) >= 1) {
                return response()->json([
                    'error' => 'No puedes editar este registro. Ha pasado más de un mes desde su creación.'
                ], 403);
            }

            //Actualizar tabla principal
            $registro->update($request->only([
                'codigo_establo', 'ubigeo', 'mes_de_referencia', 'anio',
                'region', 'provincia', 'distrito', 'nombre_establo',
                'producto_razon_social', 'direccion', 'ruc'
            ]));

            //Animales
            $totalAnimales = 0;
            foreach ($request->animales as $a) {
                AgriAnimales::updateOrCreate(
                    ['id' => $a['id'] ?? null],
                    [
                        'registro_pecuario_id' => $registro->id,
                        'variedad_id' => $a['variedad_id'],
                        'total' => $a['total'],
                        'estado' => true,
                        'usuario_id' => $usuarioId,
                    ]
                );
                $totalAnimales += (int)$a['total'];
            }

            AnimalTotal::updateOrCreate(
                ['registro_pecuario_id' => $registro->id],
                ['total_animal' => $totalAnimales]
            );

            //Leche Fresca y Productos de Leche
            $totalLeche = 0;
            $lecheFresca = LecheFresca::updateOrCreate(
                ['registro_pecuario_id' => $registro->id],
                ['total_leche' => 0]
            );

            foreach ($request->producto_leches as $p) {
                AgriProductoLeche::updateOrCreate(
                    ['id' => $p['id'] ?? null],
                    [
                        'registro_pecuario_id' => $registro->id,
                        'leche_fresca_id' => $lecheFresca->id,
                        'agri_destinos_id' => $p['agri_destinos_id'],
                        'cantidad' => $p['cantidad'],
                        'precio' => $p['precio'],
                        'usuario_id' => $usuarioId,
                    ]
                );
                $totalLeche += (float)$p['cantidad'];
            }

            $lecheFresca->update(['total_leche' => $totalLeche]);

            //Saca Reproducción
            foreach ($request->saca_reproduccion as $sr) {
                SacaReproduccion::updateOrCreate(
                    ['id' => $sr['id'] ?? null],
                    [
                        'saca_unidad' => $sr['saca_unidad'],
                        'precio_venta' => $sr['precio_venta'],
                        'id_agri_registro_pecuario' => $registro->id,
                        'id_agri_variedad_animal' => $sr['id_agri_variedad_animal'],
                        'usuario_id' => $usuarioId,
                    ]
                );
            }

            //Saca Vacuno Descarte
            foreach ($request->saca_vacuno_descarte as $sd) {
                SacaVacunoDescarte::updateOrCreate(
                    ['id' => $sd['id'] ?? null],
                    [
                        'saca_unidad' => $sd['saca_unidad'],
                        'precio_venta' => $sd['precio_venta'],
                        'peso_promedio_vivo' => $sd['peso_promedio_vivo'],
                        'id_agri_registro_pecuario' => $registro->id,
                        'id_agri_variedad_animal' => $sd['id_agri_variedad_animal'],
                        'usuario_id' => $usuarioId,
                    ]
                );
            }

            //Total de Saca
            $totalSaca = collect($request->saca_reproduccion)->sum('saca_unidad')
                        + collect($request->saca_vacuno_descarte)->sum('saca_unidad');

            AgriSacaTotal::updateOrCreate(
                ['id_agri_registro_pecuario' => $registro->id],
                ['total_leche' => $totalSaca]
            );

            //Natalidad y Mortalidad
            foreach ($request->natalidad as $n) {
                AgriNatalidad::updateOrCreate(
                    ['id' => $n['id'] ?? null],
                    [
                        'id_agri_registro_pecuario' => $registro->id,
                        'natalidad_mortalidad_id' => $n['natalidad_mortalidad_id'],
                        'cantidad' => $n['cantidad'],
                    ]
                );
            }

            foreach ($request->mortalidad as $m) {
                AgriMortalidad::updateOrCreate(
                    ['id' => $m['id'] ?? null],
                    [
                        'id_agri_registro_pecuario' => $registro->id,
                        'id_agri_variedad_animal' => $m['id_agri_variedad_animal'],
                        'cantidad' => $m['cantidad'],
                    ]
                );
            }

            //Informe Técnico
            if ($request->has('informe_tecnico')) {
                $info = $request->informe_tecnico;
                InformeTecnico::updateOrCreate(
                    ['id_agri_registro_pecuario' => $registro->id],
                    [
                        'informante' => $info['informante'],
                        'email' => $info['email'] ?? null,
                        'telefono' => $info['telefono'] ?? null,
                        'cargo' => $info['cargo'],
                        'tecnico' => $info['tecnico'],
                        'observaciones' => $info['observaciones'] ?? null,
                        'fecha' => $info['fecha'],
                    ]
                );
            }

            DB::commit();

            return response()->json(['message' => 'Registro pecuario actualizado correctamente.'], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Registro pecuario no encontrado.'], 404);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrió un error al actualizar los datos',
                'detalles' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AgriRegistroPecuario $agriRegistroPecuario)
    {
        //
    }

    public function search(Request $request)
    {
        $query = AgriRegistroPecuario::query();

        if ($request->filled(['fecha_inicio', 'fecha_fin'])) {
            $query->whereHas('informeTecnico', function ($q) use ($request) {
                $q->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin]);
            });
        }

        if ($request->filled('tecnico')) {
            $query->whereHas('informeTecnico', function ($q) use ($request) {
                $q->where('tecnico', 'LIKE', "%{$request->tecnico}%");
            });
        }

        $resultados = $query
            ->with(['informeTecnico', 'animales.variedad'])
            ->get();

        return response()->json([
            'success' => true,
            'total_resultados' => $resultados->count(),
            'data' => $resultados
        ]);
    }
}
