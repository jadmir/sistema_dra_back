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

        //MAPEAR Y AÑADIR "editable" A CADA REGISTRO
        $items = collect($registros->items())->map(function ($r) {
            // created_at ya es Carbon (porque es Eloquent)
            $editable = $r->created_at->diffInDays(now()) <= 30;

            // convertimos el modelo a array y añadimos editable
            $arr = $r->toArray();
            $arr['editable'] = $editable;

            return $arr;
        });

        return response()->json([
            'data' => $items,
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
                $info = $request->input('informe_tecnico');
                InformeTecnico::create([
                    'id_agri_registro_pecuario' => $registro->id,
                    'informante' => $info['informante'] ?? null,
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

            //editar (30 días)
            $editable = $registro->created_at->diffInDays(now()) <= 30;

            return response()->json([
                'success' => true,
                'message' => 'Registro pecuario encontrado.',
                'data' => $registro,
                'editable' => $editable
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

            //Bloqueo si el registro tiene más de 1 mes
            if ($registro->created_at->diffInDays(now()) > 30) {
                return response()->json([
                    'error' => 'No puedes editar este registro. Ha pasado más de un mes desde su creación.'
                ], 403);
            }

            //Actualizar tabla principal
            $registro->update($request->only([
                'codigo_establo',
                'ubigeo',
                'mes_de_referencia',
                'anio',
                'region',
                'provincia',
                'distrito',
                'nombre_establo',
                'producto_razon_social',
                'direccion',
                'ruc'
            ]));

            // Eliminacion (SoftDelete)
            $idsAnimales = collect($request->input('animales', []))->pluck('id')->filter()->all();
            $registro->animales()->whereNotIn('id', $idsAnimales)->delete();

            $idsProductos = collect($request->input('producto_leches', []))->pluck('id')->filter()->all();
            $registro->productosLeche()->whereNotIn('id', $idsProductos)->delete();

            $idsSacaRepr = collect($request->input('saca_reproduccion', []))->pluck('id')->filter()->all();
            $registro->sacaReproduccion()->whereNotIn('id', $idsSacaRepr)->delete();

            $idsSacaVac = collect($request->input('saca_vacuno_descarte', []))->pluck('id')->filter()->all();
            $registro->sacaVacunoDescarte()->whereNotIn('id', $idsSacaVac)->delete();

            $idsNatalidad = collect($request->input('natalidad', []))->pluck('id')->filter()->all();
            $registro->natalidad()->whereNotIn('id', $idsNatalidad)->delete();

            $idsMortalidad = collect($request->input('mortalidad', []))->pluck('id')->filter()->all();
            $registro->mortalidad()->whereNotIn('id', $idsMortalidad)->delete();

            //Animales
            $totalAnimales = 0;

            foreach ($request->input('animales', []) as $a) {
                // si viene id -> actualizar/restore por id
                if (!empty($a['id'])) {
                    AgriAnimales::withTrashed()->updateOrCreate(
                        ['id' => $a['id']],
                        [
                            'registro_pecuario_id' => $registro->id,
                            'variedad_id' => $a['variedad_id'],
                            'total' => $a['total'],
                            'estado' => true,
                            'usuario_id' => $usuarioId,
                            'deleted_at' => null
                        ]
                    );
                } else {
                    // buscar trashed que coincida en claves
                    $found = AgriAnimales::withTrashed()
                        ->where('registro_pecuario_id', $registro->id)
                        ->where('variedad_id', $a['variedad_id'])
                        ->first();

                    if ($found) {
                        if ($found->trashed()) {
                            $found->restore();
                        }
                        $found->update([
                            'total' => $a['total'],
                            'estado' => true,
                            'usuario_id' => $usuarioId,
                        ]);
                    } else {
                        AgriAnimales::create([
                            'registro_pecuario_id' => $registro->id,
                            'variedad_id' => $a['variedad_id'],
                            'total' => $a['total'],
                            'estado' => true,
                            'usuario_id' => $usuarioId,
                        ]);
                    }
                }
                $totalAnimales += (int) ($a['total'] ?? 0);
            }

            AnimalTotal::updateOrCreate(
                ['registro_pecuario_id' => $registro->id],
                ['total_animal' => $totalAnimales]
            );

            // ---- Leche Fresca y Productos de Leche (clave sugerida: registro + agri_destinos_id) ----
            $totalLeche = 0;
            $lecheFresca = LecheFresca::updateOrCreate(
                ['registro_pecuario_id' => $registro->id],
                ['total_leche' => 0]
            );

            foreach ($request->input('producto_leches', []) as $p) {
                if (!empty($p['id'])) {
                    AgriProductoLeche::withTrashed()->updateOrCreate(
                        ['id' => $p['id']],
                        [
                            'registro_pecuario_id' => $registro->id,
                            'leche_fresca_id' => $lecheFresca->id,
                            'agri_destinos_id' => $p['agri_destinos_id'] ?? null,
                            'cantidad' => $p['cantidad'] ?? null,
                            'precio' => $p['precio'] ?? null,
                            'usuario_id' => $usuarioId,
                            'deleted_at' => null
                        ]
                    );
                } else {
                    $found = AgriProductoLeche::withTrashed()
                        ->where('registro_pecuario_id', $registro->id)
                        ->where('agri_destinos_id', $p['agri_destinos_id'] ?? null)
                        ->first();

                    if ($found) {
                        if ($found->trashed()) {
                            $found->restore();
                        }
                        $found->update([
                            'cantidad' => $p['cantidad'] ?? null,
                            'precio' => $p['precio'] ?? null,
                            'usuario_id' => $usuarioId,
                            'leche_fresca_id' => $lecheFresca->id,
                        ]);
                    } else {
                        AgriProductoLeche::create([
                            'registro_pecuario_id' => $registro->id,
                            'leche_fresca_id' => $lecheFresca->id,
                            'agri_destinos_id' => $p['agri_destinos_id'] ?? null,
                            'cantidad' => $p['cantidad'] ?? null,
                            'precio' => $p['precio'] ?? null,
                            'usuario_id' => $usuarioId,
                        ]);
                    }
                }
                $totalLeche += (float)($p['cantidad'] ?? 0);
            }

            $lecheFresca->update(['total_leche' => $totalLeche]);

            // ---- Saca Reproducción (clave: registro + id_agri_variedad_animal) ----
            foreach ($request->input('saca_reproduccion', []) as $sr) {
                if (!empty($sr['id'])) {
                    SacaReproduccion::withTrashed()->updateOrCreate(
                        ['id' => $sr['id']],
                        [
                            'saca_unidad' => $sr['saca_unidad'] ?? null,
                            'precio_venta' => $sr['precio_venta'] ?? null,
                            'id_agri_registro_pecuario' => $registro->id,
                            'id_agri_variedad_animal' => $sr['id_agri_variedad_animal'] ?? null,
                            'usuario_id' => $usuarioId,
                            'deleted_at' => null
                        ]
                    );
                } else {
                    $found = SacaReproduccion::withTrashed()
                        ->where('id_agri_registro_pecuario', $registro->id)
                        ->where('id_agri_variedad_animal', $sr['id_agri_variedad_animal'] ?? null)
                        ->first();

                    if ($found) {
                        if ($found->trashed()) $found->restore();
                        $found->update([
                            'saca_unidad' => $sr['saca_unidad'] ?? null,
                            'precio_venta' => $sr['precio_venta'] ?? null,
                            'usuario_id' => $usuarioId,
                        ]);
                    } else {
                        SacaReproduccion::create([
                            'id_agri_registro_pecuario' => $registro->id,
                            'id_agri_variedad_animal' => $sr['id_agri_variedad_animal'] ?? null,
                            'saca_unidad' => $sr['saca_unidad'] ?? null,
                            'precio_venta' => $sr['precio_venta'] ?? null,
                            'usuario_id' => $usuarioId,
                        ]);
                    }
                }
            }

            // ---- Saca Vacuno Descarte ----
            foreach ($request->input('saca_vacuno_descarte', []) as $sd) {
                if (!empty($sd['id'])) {
                    SacaVacunoDescarte::withTrashed()->updateOrCreate(
                        ['id' => $sd['id']],
                        [
                            'saca_unidad' => $sd['saca_unidad'] ?? null,
                            'precio_venta' => $sd['precio_venta'] ?? null,
                            'peso_promedio_vivo' => $sd['peso_promedio_vivo'] ?? null,
                            'id_agri_registro_pecuario' => $registro->id,
                            'id_agri_variedad_animal' => $sd['id_agri_variedad_animal'] ?? null,
                            'usuario_id' => $usuarioId,
                            'deleted_at' => null
                        ]
                    );
                } else {
                    $found = SacaVacunoDescarte::withTrashed()
                        ->where('id_agri_registro_pecuario', $registro->id)
                        ->where('id_agri_variedad_animal', $sd['id_agri_variedad_animal'] ?? null)
                        ->first();

                    if ($found) {
                        if ($found->trashed()) $found->restore();
                        $found->update([
                            'saca_unidad' => $sd['saca_unidad'] ?? null,
                            'precio_venta' => $sd['precio_venta'] ?? null,
                            'peso_promedio_vivo' => $sd['peso_promedio_vivo'] ?? null,
                            'usuario_id' => $usuarioId,
                        ]);
                    } else {
                        SacaVacunoDescarte::create([
                            'id_agri_registro_pecuario' => $registro->id,
                            'id_agri_variedad_animal' => $sd['id_agri_variedad_animal'] ?? null,
                            'saca_unidad' => $sd['saca_unidad'] ?? null,
                            'precio_venta' => $sd['precio_venta'] ?? null,
                            'peso_promedio_vivo' => $sd['peso_promedio_vivo'] ?? null,
                            'usuario_id' => $usuarioId,
                        ]);
                    }
                }
            }

            // ---- Recalcular total saca y guardar ----
            $totalSaca = collect($request->input('saca_reproduccion', []))->sum('saca_unidad')
                + collect($request->input('saca_vacuno_descarte', []))->sum('saca_unidad');

            AgriSacaTotal::updateOrCreate(
                ['id_agri_registro_pecuario' => $registro->id],
                ['total_leche' => $totalSaca]
            );

            // ---- Natalidad ----
            foreach ($request->input('natalidad', []) as $n) {
                if (!empty($n['id'])) {
                    AgriNatalidad::withTrashed()->updateOrCreate(
                        ['id' => $n['id']],
                        [
                            'id_agri_registro_pecuario' => $registro->id,
                            'natalidad_mortalidad_id' => $n['natalidad_mortalidad_id'] ?? null,
                            'cantidad' => $n['cantidad'] ?? null,
                            'deleted_at' => null
                        ]
                    );
                } else {
                    $found = AgriNatalidad::withTrashed()
                        ->where('id_agri_registro_pecuario', $registro->id)
                        ->where('natalidad_mortalidad_id', $n['natalidad_mortalidad_id'] ?? null)
                        ->first();

                    if ($found) {
                        if ($found->trashed()) $found->restore();
                        $found->update([
                            'cantidad' => $n['cantidad'] ?? null,
                        ]);
                    } else {
                        AgriNatalidad::create([
                            'id_agri_registro_pecuario' => $registro->id,
                            'natalidad_mortalidad_id' => $n['natalidad_mortalidad_id'] ?? null,
                            'cantidad' => $n['cantidad'] ?? null,
                        ]);
                    }
                }
            }

            // ---- Mortalidad ----
            foreach ($request->input('mortalidad', []) as $m) {
                if (!empty($m['id'])) {
                    AgriMortalidad::withTrashed()->updateOrCreate(
                        ['id' => $m['id']],
                        [
                            'id_agri_registro_pecuario' => $registro->id,
                            'id_agri_variedad_animal' => $m['id_agri_variedad_animal'] ?? null,
                            'cantidad' => $m['cantidad'] ?? null,
                            'deleted_at' => null
                        ]
                    );
                } else {
                    $found = AgriMortalidad::withTrashed()
                        ->where('id_agri_registro_pecuario', $registro->id)
                        ->where('id_agri_variedad_animal', $m['id_agri_variedad_animal'] ?? null)
                        ->first();

                    if ($found) {
                        if ($found->trashed()) $found->restore();
                        $found->update([
                            'cantidad' => $m['cantidad'] ?? null,
                        ]);
                    } else {
                        AgriMortalidad::create([
                            'id_agri_registro_pecuario' => $registro->id,
                            'id_agri_variedad_animal' => $m['id_agri_variedad_animal'] ?? null,
                            'cantidad' => $m['cantidad'] ?? null,
                        ]);
                    }
                }
            }

            // ---- Informe Técnico (one-to-one) ----
            if ($request->has('informe_tecnico')) {
                $info = $request->input('informe_tecnico');
                InformeTecnico::updateOrCreate(
                    ['id_agri_registro_pecuario' => $registro->id],
                    [
                        'informante' => $info['informante'] ?? null,
                        'email' => $info['email'] ?? null,
                        'telefono' => $info['telefono'] ?? null,
                        'cargo' => $info['cargo'] ?? null,
                        'tecnico' => $info['tecnico'] ?? null,
                        'observaciones' => $info['observaciones'] ?? null,
                        'fecha' => $info['fecha'] ?? null,
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
