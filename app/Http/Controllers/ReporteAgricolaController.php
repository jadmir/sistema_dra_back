<?php

namespace App\Http\Controllers;

use App\Exports\RegistroAgricolaExport;
use App\Exports\RegistroAgricolaPeriodoExport;
use App\Models\AgriRegistro;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReporteAgricolaController extends Controller
{
    // exportar excel por ID
    public function exportExcel(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:agri_registros,id',
        ]);

        $registro = AgriRegistro::with([
            'region',
            'provincia',
            'distrito',
            'detalles.cultivo',
            'detalles.variables.variableCatalogo.unidad'
        ])->findOrFail($request->id);

        return Excel::download(new RegistroAgricolaExport($registro), "registro_agricola_{$registro->anio}.xlsx");
    }


    // Exporta Excel por periodo (histórico)
    public function exportPeriodo(Request $request)
    {
        Log::info('Export periodo recibidos: ' . json_encode($request->all()));

        try {
            $request->validate([
                'anio_inicio' => 'required|integer',
                'mes_inicio' => 'required|integer|min:1|max:12',
                'anio_fin' => 'required|integer',
                'mes_fin' => 'required|integer|min:1|max:12',
                'region_id' => 'required|integer',
            ]);

            $anioInicio = (int)$request->anio_inicio;
            $mesInicio = (int)$request->mes_inicio;
            $anioFin = (int)$request->anio_fin;
            $mesFin = (int)$request->mes_fin;
            $regionId = (int)$request->region_id;

            $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'set', 'oct', 'nov', 'dic'];

            // Obtener registros filtrados
            $registrosQuery = AgriRegistro::with([
                'detalles.cultivo',
                'detalles.variables.variableCatalogo.unidad'
            ])
                ->where('region_id', $regionId)
                ->whereBetween('anio', [$anioInicio, $anioFin])
                ->get();

            $registrosData = [];

            foreach ($registrosQuery as $registro) {
                foreach ($registro->detalles as $detalle) {
                    foreach ($detalle->variables as $variable) {

                        $mesesData = [];

                        // Guardar datos por año y mes
                        for ($i = 0; $i < 12; $i++) {
                            $mesActual = $meses[$i];
                            $mesesData[$registro->anio][$i + 1] = is_numeric($variable->$mesActual) ? (float)$variable->$mesActual : 0;
                        }

                        $registrosData[] = [
                            'cultivo' => $detalle->cultivo->nombre ?? '',
                            'variable' => $variable->variableCatalogo->nombre ?? '',
                            'unidad' => $variable->variableCatalogo->unidad->nombre ?? '',
                            'meses' => $mesesData,
                        ];
                    }
                }
            }

            // cultivo agrupado
            $registrosAgrupados = [];
            foreach ($registrosData as $registro) {
                $cultivo = $registro['cultivo'];
                if (!isset($registrosAgrupados[$cultivo])) {
                    $registrosAgrupados[$cultivo] = [
                        'cultivo' => $cultivo,
                        'variables' => []
                    ];
                }
                $registrosAgrupados[$cultivo]['variables'][] = [
                    'variable' => $registro['variable'],
                    'unidad' => $registro['unidad'],
                    'meses' => $registro['meses'],
                ];
            }

            return Excel::download(
                new RegistroAgricolaPeriodoExport(array_values($registrosAgrupados), $anioInicio, $mesInicio, $anioFin, $mesFin),
                "reporte_periodo_{$anioInicio}_{$anioFin}.xlsx"
            );
        } catch (\Throwable $e) {
            Log::error('Error exportando Excel periodo: ' . $e->getMessage());
            return response()->json(['error' => 'Error exportando Excel periodo'], 500);
        }
    }
}
