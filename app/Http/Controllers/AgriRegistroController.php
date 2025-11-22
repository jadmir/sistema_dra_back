<?php

namespace App\Http\Controllers;

use App\Models\AgriRegistro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgriRegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AgriRegistro::with([
            'usuario',
            'region',
            'provincia',
            'distrito',
            'detalles.variable'
        ])->where('estado', true);

        // Filtros dinámicos
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        }

        return response()->json($query->paginate($request->get('per_page', 10)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
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

            //Crear registro principal
            $registro = AgriRegistro::create([
                'distrito_id' => $validated['distrito_id'],
                'anio' => $validated['anio'],
                'observacion' => $validated['observacion'] ?? null,
                'usuario_id' => Auth::id(),
                'estado' => true,
            ]);

            //Registrar detalles (cultivos)
            foreach ($validated['detalles'] as $detalle) {
                $detalleRegistro = \App\Models\AgriRegistroDetalle::create([
                    'registro_id' => $registro->id,
                    'cultivo_id' => $detalle['cultivo_id'],
                    'usuario_id' => Auth::id(),
                ]);

                //Registrar variables (por cultivo)
                foreach ($detalle['variables'] as $variable) {
                    $valores = collect($variable)->only([
                        'ene', 'feb', 'mar', 'abr', 'may', 'jun', 
                        'jul', 'ago', 'sep', 'oct', 'nov', 'dic'
                    ])->map(fn($v) => $v ?? 0);

                    $total = $valores->sum();

                    \App\Models\AgriRegistroVariable::create([
                        'detalle_id' => $detalleRegistro->id,
                        'variable_id' => $variable['variable_id'],
                        'total_anual' => $total,
                        'usuario_id' => Auth::id(),
                        ...$valores
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
    public function update(Request $request, AgriRegistro $agriRegistro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AgriRegistro $agriRegistro)
    {
        //
    }
}
