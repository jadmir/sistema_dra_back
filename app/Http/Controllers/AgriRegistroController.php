<?php

namespace App\Http\Controllers;

use App\Models\AgriRegistro;
use App\Models\AgriRegion;
use App\Models\AgriProvincia;
use App\Models\AgriDistrito;
use App\Models\AgriRegistroDetalle;
use App\Models\AgriRegistroVariable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RegistroAgricolaCampaniaExport;

class AgriRegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $query = AgriRegistro::with([
            'distrito.provincia.region',
            'detalles.cultivo',
            'detalles.variables.variableCatalogo'
        ])->where('estado', true);

        //filtro region
        if ($request->filled('region_id')) {
            $query->whereHas('distrito.provincia', function ($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        }

        //filtro año
        if ($request->filled('anio')) {
            $query->where('anio', $request->anio);
        }

        //filtro cultivo
        if ($request->filled('cultivos') && is_array($request->cultivos)) {
            $query->whereHas('detalles.cultivo', function ($q) use ($request) {
                $q->whereIn('id', $request->cultivos);
            });
        }

        //busqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('distrito', function ($qd) use ($search) {
                    $qd->where('nombre', 'like', "%$search%");
                })->orWhereHas('detalles.cultivo', function ($qc) use ($search) {
                    $qc->where('nombre', 'like', "%$search%");
                });
            });
        }

        $result = $query->paginate($perPage);

        return response()->json($result);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validación
            $validated = $request->validate([
                'region_id' => 'required|exists:agri_regiones,id',
                'provincia_id' => 'required|exists:agri_provincias,id',
                'distrito_id' => 'required|exists:agri_distritos,id',
                'anio' => 'required|digits:4|integer|min:2000',
                'observacion' => 'nullable|string',
                'detalles' => 'required|array',
                'detalles.*.cultivo_id' => 'required|exists:agri_cultivo_catalogos,id',
                'detalles.*.variables' => 'required|array',
                'detalles.*.variables.*.variable_id' => 'required|exists:agri_variable_catalogos,id',
                'detalles.*.variables.*.ene' => 'nullable|numeric',
                'detalles.*.variables.*.feb' => 'nullable|numeric',
                'detalles.*.variables.*.mar' => 'nullable|numeric',
                'detalles.*.variables.*.abr' => 'nullable|numeric',
                'detalles.*.variables.*.may' => 'nullable|numeric',
                'detalles.*.variables.*.jun' => 'nullable|numeric',
                'detalles.*.variables.*.jul' => 'nullable|numeric',
                'detalles.*.variables.*.ago' => 'nullable|numeric',
                'detalles.*.variables.*.sep' => 'nullable|numeric',
                'detalles.*.variables.*.oct' => 'nullable|numeric',
                'detalles.*.variables.*.nov' => 'nullable|numeric',
                'detalles.*.variables.*.dic' => 'nullable|numeric',
            ]);

            // Crear registro principal
            $registro = \App\Models\AgriRegistro::create([
                'region_id' => $validated['region_id'],
                'provincia_id' => $validated['provincia_id'],
                'distrito_id' => $validated['distrito_id'],
                'anio' => $validated['anio'],
                'observacion' => $validated['observacion'] ?? null,
                'usuario_id' => Auth::id(),
                'estado' => true,
            ]);

            // Registrar detalles (cultivos)
            foreach ($validated['detalles'] as $detalle) {
                $detalleRegistro = \App\Models\AgriRegistroDetalle::create([
                    'registro_id' => $registro->id,
                    'cultivo_id' => $detalle['cultivo_id'],
                    'usuario_id' => Auth::id(),
                ]);

                // Registrar variables (por cultivo)
                foreach ($detalle['variables'] as $variable) {
                    // Tomar solo los meses y reemplazar null por 0
                    $valores = collect($variable)->only([
                        'ene',
                        'feb',
                        'mar',
                        'abr',
                        'may',
                        'jun',
                        'jul',
                        'ago',
                        'sep',
                        'oct',
                        'nov',
                        'dic'
                    ])->map(fn($v) => $v ?? 0);

                    $total = $valores->sum();

                    \App\Models\AgriRegistroVariable::create([
                        'detalle_id' => $detalleRegistro->id,
                        'variable_id' => $variable['variable_id'],
                        'total_anual' => $total,
                        'usuario_id' => Auth::id(),
                        ...$valores, // Destructurar los meses
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Registro creado correctamente.',
                'data' => $registro->load('detalles.variables')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'Error al crear el registro',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AgriRegistro $agriRegistro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $validated = $request->validate([
                'region_id' => 'required|exists:agri_regiones,id',
                'provincia_id' => 'required|exists:agri_provincias,id',
                'distrito_id' => 'required|exists:agri_distritos,id',
                'anio' => 'required|digits:4|integer|min:2000',
                'observacion' => 'nullable|string',

                'detalles' => 'required|array',

                'detalles.*.id' => 'nullable|integer',
                'detalles.*.cultivo_id' => 'required|exists:agri_cultivo_catalogos,id',

                'detalles.*.variables' => 'required|array',

                'detalles.*.variables.*.id' => 'nullable|integer',
                'detalles.*.variables.*.variable_id' => 'required|exists:agri_variable_catalogos,id',

                'detalles.*.variables.*.*' => 'nullable|numeric'
            ]);

            $registro = AgriRegistro::findOrFail($id);

            $registro->update([
                'region_id' => $validated['region_id'],
                'provincia_id' => $validated['provincia_id'],
                'distrito_id' => $validated['distrito_id'],
                'anio' => $validated['anio'],
                'observacion' => $validated['observacion'] ?? null,
                'usuario_id' => Auth::id(),
            ]);

            $detalleIdsRequest = collect($validated['detalles'])
                ->pluck('id')
                ->filter()
                ->toArray();

            $registro->detalles()
                ->whereNotIn('id', $detalleIdsRequest)
                ->delete();

            foreach ($validated['detalles'] as $detalleData) {

                $detalle = AgriRegistroDetalle::withTrashed()
                    ->where('id', $detalleData['id'] ?? 0)
                    ->where('registro_id', $registro->id)
                    ->first();

                if ($detalle) {
                    if ($detalle->trashed()) {
                        $detalle->restore();
                    }

                    $detalle->update([
                        'cultivo_id' => $detalleData['cultivo_id'],
                        'usuario_id' => Auth::id(),
                    ]);
                } else {
                    $detalle = AgriRegistroDetalle::create([
                        'registro_id' => $registro->id,
                        'cultivo_id' => $detalleData['cultivo_id'],
                        'usuario_id' => Auth::id(),
                    ]);
                }

                $variablesRequest = collect($detalleData['variables']);
                $variablesRequestIds = $variablesRequest->pluck('id')->filter()->toArray();

                $detalle->variables()
                    ->whereNotIn('id', $variablesRequestIds)
                    ->delete();

                foreach ($variablesRequest as $varData) {

                    $variable = AgriRegistroVariable::withTrashed()
                        ->where('id', $varData['id'] ?? 0)
                        ->where('detalle_id', $detalle->id)
                        ->first();

                    $meses = collect($varData)->only([
                        'ene',
                        'feb',
                        'mar',
                        'abr',
                        'may',
                        'jun',
                        'jul',
                        'ago',
                        'sep',
                        'oct',
                        'nov',
                        'dic'
                    ])->map(fn($v) => $v ?? 0);

                    $total = $meses->sum();

                    if ($variable) {

                        if ($variable->trashed()) {
                            $variable->restore();
                        }

                        $variable->update([
                            'variable_id' => $varData['variable_id'],
                            'total_anual' => $total,
                            'usuario_id' => Auth::id(),
                            ...$meses,
                        ]);
                    } else {
                        AgriRegistroVariable::create([
                            'detalle_id' => $detalle->id,
                            'variable_id' => $varData['variable_id'],
                            'total_anual' => $total,
                            'usuario_id' => Auth::id(),
                            ...$meses,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Registro actualizado correctamente.',
                'data' => $registro->load('detalles.variables')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AgriRegistro $agriRegistro)
    {
        //
    }


    public function exportCampania(Request $request)
    {
        $query = AgriRegistro::query()->with([
            'detalles.cultivo',
            'detalles.variables',
        ]);

        if ($request->region)    $query->where('region_id', $request->region);
        if ($request->provincia) $query->where('provincia_id', $request->provincia);
        if ($request->distrito)  $query->where('distrito_id', $request->distrito);

        $selectedCultivos = $request->cultivos ?? [];

        // Si NO está “TODOS” (0) y hay cultivos seleccionados
        if (!in_array(0, $selectedCultivos) && count($selectedCultivos) > 0) {
            $query->whereHas('detalles', function ($q) use ($selectedCultivos) {
                $q->whereIn('cultivo_id', $selectedCultivos);
            });
        }

        //filtro campaña
        $anioInicio = intval($request->anio_inicio);
        $anioFin    = intval($request->anio_fin);

        if ($anioInicio && $anioFin) {
            $query->whereBetween('anio', [$anioInicio, $anioFin]);
        }

        $registros = $query->get();

        $mesesMap = [
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic'
        ];

        $mesInicio = intval($request->mes_inicio);
        $mesFin    = intval($request->mes_fin);

        $mesesRango = [];
        for ($anio = $anioInicio; $anio <= $anioFin; $anio++) {
            $inicioMes = ($anio === $anioInicio) ? $mesInicio : 1;
            $finMes    = ($anio === $anioFin) ? $mesFin : 12;

            for ($m = $inicioMes; $m <= $finMes; $m++) {
                $mesesRango[] = ['anio' => $anio, 'mes' => $m];
            }
        }

        $totalGeneralMeses = [];
        foreach ($mesesRango as $item) {
            $key = $item['anio'] . '-' . $item['mes'];
            $totalGeneralMeses[$key] = 0;
        }

        $totalGeneralCampania = 0;
        $productos = [];

        foreach ($registros as $registro) {
            foreach ($registro->detalles as $detalle) {

                if (!in_array(0, $selectedCultivos) && count($selectedCultivos) > 0) {
                    if (!in_array($detalle->cultivo_id, $selectedCultivos)) {
                        continue;
                    }
                }
                $detalle->variablesFiltradas = collect();

                foreach ($detalle->variables as $variable) {

                    $producto = $detalle->cultivo->nombre ?? 'SIN CULTIVO';

                    if (!isset($productos[$producto])) {
                        $productos[$producto] = [
                            'meses' => [],
                            'total' => 0
                        ];

                        foreach ($mesesRango as $item) {
                            $key = $item['anio'] . '-' . $item['mes'];
                            $productos[$producto]['meses'][$key] = 0;
                        }
                    }

                    $totalCampania = 0;

                    foreach ($mesesRango as $item) {
                        if ($registro->anio == $item['anio']) {
                            $key = $item['anio'] . '-' . $item['mes'];
                            $col = $mesesMap[$item['mes']];
                            $valor = floatval($variable->$col ?? 0);

                            $productos[$producto]['meses'][$key] += $valor;
                            $totalGeneralMeses[$key] += $valor;
                            $totalCampania += $valor;
                        }
                    }

                    $var = clone $variable;
                    $var->total_campania = $totalCampania;

                    $detalle->variablesFiltradas->push($var);

                    $productos[$producto]['total'] += $totalCampania;
                    $totalGeneralCampania += $totalCampania;
                }
            }
        }

        $regionNombre    = AgriRegion::find($request->region)?->nombre ?? 'Todas';
        $provinciaNombre = AgriProvincia::find($request->provincia)?->nombre ?? 'Todas';
        $distritoNombre  = AgriDistrito::find($request->distrito)?->nombre ?? 'Todas';

        return Excel::download(
            new RegistroAgricolaCampaniaExport(
                $registros,
                $anioInicio,
                $anioFin,
                $mesInicio,
                $mesFin,
                $regionNombre,
                $provinciaNombre,
                $distritoNombre,
            ),
            'registro_campania.xlsx'
        );
    }
}
