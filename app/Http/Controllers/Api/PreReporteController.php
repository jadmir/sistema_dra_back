<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreMuestra;
use App\Models\PreReporteComparativo;
use App\Models\PreProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PreReporteController extends Controller
{
    /**
     * Reporte comparativo mayorista vs minorista
     * GET /api/precios/reportes/comparativo?fecha=2025-11-17&producto_id=1
     */
    public function comparativo(Request $request)
    {
        try {
            $fecha = $request->fecha ?? today()->toDateString();

            $query = PreReporteComparativo::with(['producto', 'ubicacion'])
                                        ->fecha($fecha);

            if ($request->has('producto_id')) {
                $query->where('producto_id', $request->producto_id);
            }

            if ($request->has('ubicacion_id')) {
                $query->where('ubicacion_id', $request->ubicacion_id);
            }

            $reportes = $query->orderBy('variacion_porcentual', 'desc')->get();

            // Agregar advertencias a cada reporte
            $reportes->transform(function($reporte) {
                $advertencias = [];

                // Verificar si faltan datos de minoristas
                if (!$reporte->precio_minorista_promedio || $reporte->num_muestras_minoristas == 0) {
                    $advertencias[] = 'Faltan datos de mercados MINORISTAS. Agregue muestras de precios minoristas para un mejor cálculo comparativo.';
                }

                // Verificar si faltan datos de mayoristas
                if (!$reporte->precio_mayorista_promedio || $reporte->num_muestras_mayoristas == 0) {
                    $advertencias[] = 'Faltan datos de mercados MAYORISTAS. Agregue muestras de precios mayoristas para un mejor cálculo comparativo.';
                }

                // Verificar si hay pocas muestras
                if ($reporte->num_muestras_mayoristas > 0 && $reporte->num_muestras_mayoristas < 3) {
                    $advertencias[] = 'Pocas muestras mayoristas (' . $reporte->num_muestras_mayoristas . '). Se recomienda al menos 3 muestras para mayor precisión.';
                }

                if ($reporte->num_muestras_minoristas > 0 && $reporte->num_muestras_minoristas < 3) {
                    $advertencias[] = 'Pocas muestras minoristas (' . $reporte->num_muestras_minoristas . '). Se recomienda al menos 3 muestras para mayor precisión.';
                }

                // Agregar campo de advertencias al reporte
                $reporte->advertencias = $advertencias;
                $reporte->datos_completos = empty($advertencias);

                return $reporte;
            });

            return response()->json([
                'fecha' => $fecha,
                'total_productos' => $reportes->count(),
                'reportes' => $reportes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte comparativo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar reporte comparativo para una fecha específica
     * POST /api/precios/reportes/generar-comparativo
     */
    public function generarComparativo(Request $request)
    {
        $fecha = $request->fecha ?? today()->toDateString();

        try {
            DB::beginTransaction();

            // Obtener todos los productos con muestras validadas en la fecha
            $productos = PreProducto::whereHas('muestras', function($q) use ($fecha) {
                $q->fecha($fecha)->validado();
            })->get();

            $reportesCreados = 0;

            foreach ($productos as $producto) {
                // Calcular promedios MAYORISTAS
                $mayoristas = DB::table('pre_muestras as m')
                    ->join('pre_mercados as me', 'm.mercado_id', '=', 'me.id')
                    ->where('m.producto_id', $producto->id)
                    ->where('m.fecha', $fecha)
                    ->where('m.validado', true)
                    ->where('me.tipo', 'MAYORISTA')
                    ->select(
                        DB::raw('AVG(m.precio) as promedio'),
                        DB::raw('MIN(m.precio) as minimo'),
                        DB::raw('MAX(m.precio) as maximo'),
                        DB::raw('COUNT(DISTINCT me.id) as num_mercados'),
                        DB::raw('COUNT(*) as num_muestras')
                    )
                    ->first();

                // Calcular promedios MINORISTAS
                $minoristas = DB::table('pre_muestras as m')
                    ->join('pre_mercados as me', 'm.mercado_id', '=', 'me.id')
                    ->where('m.producto_id', $producto->id)
                    ->where('m.fecha', $fecha)
                    ->where('m.validado', true)
                    ->where('me.tipo', 'MINORISTA')
                    ->select(
                        DB::raw('AVG(m.precio) as promedio'),
                        DB::raw('MIN(m.precio) as minimo'),
                        DB::raw('MAX(m.precio) as maximo'),
                        DB::raw('COUNT(DISTINCT me.id) as num_mercados'),
                        DB::raw('COUNT(*) as num_muestras')
                    )
                    ->first();

                // Crear reporte si hay al menos datos de MAYORISTAS
                if ($mayoristas->promedio) {
                    // Calcular variación porcentual solo si hay datos de ambos tipos
                    $variacion = null;
                    if ($minoristas->promedio && $mayoristas->promedio) {
                        $variacion = (($minoristas->promedio - $mayoristas->promedio) / $mayoristas->promedio) * 100;
                    }

                    PreReporteComparativo::updateOrCreate(
                        [
                            'producto_id' => $producto->id,
                            'fecha' => $fecha,
                            'ubicacion_id' => null
                        ],
                        [
                            'precio_mayorista_promedio' => round($mayoristas->promedio, 2),
                            'precio_mayorista_minimo' => round($mayoristas->minimo, 2),
                            'precio_mayorista_maximo' => round($mayoristas->maximo, 2),
                            'num_mercados_mayoristas' => $mayoristas->num_mercados,
                            'num_muestras_mayoristas' => $mayoristas->num_muestras,
                            'precio_minorista_promedio' => $minoristas->promedio ? round($minoristas->promedio, 2) : null,
                            'precio_minorista_minimo' => $minoristas->minimo ? round($minoristas->minimo, 2) : null,
                            'precio_minorista_maximo' => $minoristas->maximo ? round($minoristas->maximo, 2) : null,
                            'num_mercados_minoristas' => $minoristas->num_mercados ?? 0,
                            'num_muestras_minoristas' => $minoristas->num_muestras ?? 0,
                            'variacion_porcentual' => $variacion ? round($variacion, 2) : null
                        ]
                    );

                    $reportesCreados++;
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Reporte comparativo generado exitosamente',
                'fecha' => $fecha,
                'productos_procesados' => $productos->count(),
                'reportes_creados' => $reportesCreados
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al generar reporte comparativo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resumen de muestras por fecha
     * GET /api/precios/reportes/resumen-muestras?fecha=2025-11-17
     */
    public function resumenMuestras(Request $request)
    {
        try {
            $fecha = $request->fecha ?? today()->toDateString();

            $resumen = DB::table('pre_muestras as m')
                ->join('pre_mercados as me', 'm.mercado_id', '=', 'me.id')
                ->join('pre_productos as p', 'm.producto_id', '=', 'p.id')
                ->where('m.fecha', $fecha)
                ->select(
                    'p.id as producto_id',
                    'p.nombre as producto',
                    'me.tipo as tipo_mercado',
                    DB::raw('COUNT(*) as total_muestras'),
                    DB::raw('SUM(CASE WHEN m.validado = 1 THEN 1 ELSE 0 END) as muestras_validadas'),
                    DB::raw('SUM(CASE WHEN m.validado = 0 THEN 1 ELSE 0 END) as muestras_pendientes'),
                    DB::raw('AVG(m.precio) as precio_promedio'),
                    DB::raw('MIN(m.precio) as precio_minimo'),
                    DB::raw('MAX(m.precio) as precio_maximo')
                )
                ->groupBy('p.id', 'p.nombre', 'me.tipo')
                ->orderBy('p.nombre')
                ->orderBy('me.tipo')
                ->get();

            return response()->json([
                'fecha' => $fecha,
                'resumen' => $resumen
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar resumen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Histórico de precios de un producto
     * GET /api/precios/reportes/historico/{producto_id}?fecha_inicio=2025-11-01&fecha_fin=2025-11-17
     */
    public function historico($producto_id, Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio ?? today()->subDays(30)->toDateString();
            $fecha_fin = $request->fecha_fin ?? today()->toDateString();

            $historico = DB::table('pre_muestras as m')
                ->join('pre_mercados as me', 'm.mercado_id', '=', 'me.id')
                ->where('m.producto_id', $producto_id)
                ->where('m.validado', true)
                ->whereBetween('m.fecha', [$fecha_inicio, $fecha_fin])
                ->select(
                    'm.fecha',
                    'me.tipo as tipo_mercado',
                    DB::raw('AVG(m.precio) as precio_promedio'),
                    DB::raw('MIN(m.precio) as precio_minimo'),
                    DB::raw('MAX(m.precio) as precio_maximo'),
                    DB::raw('COUNT(*) as num_muestras')
                )
                ->groupBy('m.fecha', 'me.tipo')
                ->orderBy('m.fecha')
                ->orderBy('me.tipo')
                ->get();

            $producto = PreProducto::find($producto_id);

            return response()->json([
                'producto' => $producto,
                'periodo' => [
                    'inicio' => $fecha_inicio,
                    'fin' => $fecha_fin
                ],
                'historico' => $historico
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar histórico',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reporte de productividad de encuestadores (rango de fechas)
     * GET /api/precios/reportes/encuestadores/productividad?fecha_inicio=2025-11-01&fecha_fin=2025-11-30&encuestador_id=1
     */
    public function productividadEncuestadores(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio ?? today()->startOfMonth()->toDateString();
            $fecha_fin = $request->fecha_fin ?? today()->toDateString();

            $query = DB::table('pre_muestras as m')
                ->join('pre_encuestadores as e', 'm.encuestador_id', '=', 'e.id')
                ->whereBetween('m.fecha', [$fecha_inicio, $fecha_fin]);

            // Filtro opcional por encuestador
            if ($request->has('encuestador_id')) {
                $query->where('m.encuestador_id', $request->encuestador_id);
            }

            $productividad = $query
                ->select(
                    'e.id as encuestador_id',
                    'e.nombre',
                    'e.dni',
                    'e.telefono',
                    DB::raw('COUNT(*) as total_muestras'),
                    DB::raw('SUM(CASE WHEN m.validado = 1 THEN 1 ELSE 0 END) as muestras_validadas'),
                    DB::raw('SUM(CASE WHEN m.validado = 0 THEN 1 ELSE 0 END) as muestras_pendientes'),
                    DB::raw('COUNT(DISTINCT m.fecha) as dias_trabajados'),
                    DB::raw('COUNT(DISTINCT m.mercado_id) as mercados_visitados'),
                    DB::raw('COUNT(DISTINCT m.producto_id) as productos_registrados'),
                    DB::raw('ROUND(COUNT(*) / COUNT(DISTINCT m.fecha), 2) as promedio_muestras_por_dia')
                )
                ->groupBy('e.id', 'e.nombre', 'e.dni', 'e.telefono')
                ->orderBy('total_muestras', 'desc')
                ->get();

            // Calcular totales generales
            $totales = [
                'total_encuestadores' => $productividad->count(),
                'total_muestras' => $productividad->sum('total_muestras'),
                'total_validadas' => $productividad->sum('muestras_validadas'),
                'total_pendientes' => $productividad->sum('muestras_pendientes'),
                'promedio_muestras_por_encuestador' => $productividad->count() > 0
                    ? round($productividad->sum('total_muestras') / $productividad->count(), 2)
                    : 0
            ];

            return response()->json([
                'periodo' => [
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'dias' => Carbon::parse($fecha_inicio)->diffInDays(Carbon::parse($fecha_fin)) + 1
                ],
                'totales' => $totales,
                'encuestadores' => $productividad
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de productividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reporte de encuestadores por día específico
     * GET /api/precios/reportes/encuestadores/por-dia?fecha=2025-11-18
     */
    public function encuestadoresPorDia(Request $request)
    {
        try {
            $fecha = $request->fecha ?? today()->toDateString();

            $reporte = DB::table('pre_muestras as m')
                ->join('pre_encuestadores as e', 'm.encuestador_id', '=', 'e.id')
                ->join('pre_mercados as me', 'm.mercado_id', '=', 'me.id')
                ->where('m.fecha', $fecha)
                ->select(
                    'e.id as encuestador_id',
                    'e.nombre as encuestador',
                    'e.dni',
                    DB::raw('COUNT(*) as total_muestras'),
                    DB::raw('SUM(CASE WHEN m.validado = 1 THEN 1 ELSE 0 END) as validadas'),
                    DB::raw('SUM(CASE WHEN m.validado = 0 THEN 1 ELSE 0 END) as pendientes'),
                    DB::raw('COUNT(DISTINCT m.mercado_id) as mercados_visitados'),
                    DB::raw('COUNT(DISTINCT m.producto_id) as productos_registrados'),
                    DB::raw('MIN(m.created_at) as primera_muestra'),
                    DB::raw('MAX(m.created_at) as ultima_muestra')
                )
                ->groupBy('e.id', 'e.nombre', 'e.dni')
                ->orderBy('total_muestras', 'desc')
                ->get();

            // Detalle de mercados por encuestador
            $detalle_mercados = DB::table('pre_muestras as m')
                ->join('pre_encuestadores as e', 'm.encuestador_id', '=', 'e.id')
                ->join('pre_mercados as me', 'm.mercado_id', '=', 'me.id')
                ->where('m.fecha', $fecha)
                ->select(
                    'e.id as encuestador_id',
                    'e.nombre as encuestador',
                    'me.id as mercado_id',
                    'me.nombre as mercado',
                    'me.tipo as tipo_mercado',
                    DB::raw('COUNT(*) as muestras_en_mercado')
                )
                ->groupBy('e.id', 'e.nombre', 'me.id', 'me.nombre', 'me.tipo')
                ->orderBy('e.nombre')
                ->orderBy('muestras_en_mercado', 'desc')
                ->get();

            return response()->json([
                'fecha' => $fecha,
                'resumen' => [
                    'total_encuestadores' => $reporte->count(),
                    'total_muestras' => $reporte->sum('total_muestras'),
                    'muestras_validadas' => $reporte->sum('validadas'),
                    'muestras_pendientes' => $reporte->sum('pendientes')
                ],
                'encuestadores' => $reporte,
                'detalle_mercados' => $detalle_mercados->groupBy('encuestador_id')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte por día',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reporte de encuestadores por mes
     * GET /api/precios/reportes/encuestadores/por-mes?año=2025&mes=11
     */
    public function encuestadoresPorMes(Request $request)
    {
        try {
            $año = $request->año ?? now()->year;
            $mes = str_pad($request->mes ?? now()->month, 2, '0', STR_PAD_LEFT);

            $fecha_inicio = "$año-$mes-01";
            $fecha_fin = Carbon::parse($fecha_inicio)->endOfMonth()->toDateString();

            // Reporte por encuestador en el mes
            $reporte_mes = DB::table('pre_muestras as m')
                ->join('pre_encuestadores as e', 'm.encuestador_id', '=', 'e.id')
                ->whereBetween('m.fecha', [$fecha_inicio, $fecha_fin])
                ->select(
                    'e.id as encuestador_id',
                    'e.nombre',
                    'e.dni',
                    DB::raw('COUNT(*) as total_muestras'),
                    DB::raw('SUM(CASE WHEN m.validado = 1 THEN 1 ELSE 0 END) as validadas'),
                    DB::raw('SUM(CASE WHEN m.validado = 0 THEN 1 ELSE 0 END) as pendientes'),
                    DB::raw('COUNT(DISTINCT m.fecha) as dias_trabajados'),
                    DB::raw('COUNT(DISTINCT m.mercado_id) as mercados_visitados'),
                    DB::raw('COUNT(DISTINCT m.producto_id) as productos_registrados'),
                    DB::raw('ROUND(COUNT(*) / COUNT(DISTINCT m.fecha), 2) as promedio_por_dia')
                )
                ->groupBy('e.id', 'e.nombre', 'e.dni')
                ->orderBy('total_muestras', 'desc')
                ->get();

            // Reporte por día dentro del mes
            $reporte_por_dia = DB::table('pre_muestras as m')
                ->join('pre_encuestadores as e', 'm.encuestador_id', '=', 'e.id')
                ->whereBetween('m.fecha', [$fecha_inicio, $fecha_fin])
                ->select(
                    'm.fecha',
                    'e.id as encuestador_id',
                    'e.nombre as encuestador',
                    DB::raw('COUNT(*) as muestras')
                )
                ->groupBy('m.fecha', 'e.id', 'e.nombre')
                ->orderBy('m.fecha')
                ->orderBy('e.nombre')
                ->get();

            // Agrupar por fecha para crear calendario
            $calendario = $reporte_por_dia->groupBy('fecha')->map(function($dia) {
                return [
                    'total_muestras' => $dia->sum('muestras'),
                    'encuestadores_activos' => $dia->count(),
                    'detalle' => $dia->map(function($enc) {
                        return [
                            'encuestador_id' => $enc->encuestador_id,
                            'encuestador' => $enc->encuestador,
                            'muestras' => $enc->muestras
                        ];
                    })
                ];
            });

            return response()->json([
                'periodo' => [
                    'año' => $año,
                    'mes' => $mes,
                    'mes_nombre' => Carbon::parse($fecha_inicio)->locale('es')->monthName,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin
                ],
                'resumen_mes' => [
                    'total_encuestadores' => $reporte_mes->count(),
                    'total_muestras' => $reporte_mes->sum('total_muestras'),
                    'muestras_validadas' => $reporte_mes->sum('validadas'),
                    'muestras_pendientes' => $reporte_mes->sum('pendientes'),
                    'promedio_muestras_por_encuestador' => $reporte_mes->count() > 0
                        ? round($reporte_mes->sum('total_muestras') / $reporte_mes->count(), 2)
                        : 0
                ],
                'encuestadores' => $reporte_mes,
                'calendario' => $calendario
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte por mes',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
