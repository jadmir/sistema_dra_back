<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgriReporteGenerado;
use App\Models\AgriAnalisisPrecio;
use App\Models\AgriMetaMensual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Exports\AnalisisPreciosExport;
use App\Exports\AgroquimicosF6Export;
use App\Exports\AgroquimicosExport;
use App\Exports\AgroquimicosExportArray;
use App\Exports\TransporteExport;
use App\Exports\TransporteF14Export;
use App\Exports\MaquinariaExport;
use App\Exports\FertilizantesExport;
use App\Exports\FertilizantesExportArray;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AgriReporteController extends Controller
{
    /**
     * Reporte de precios de maquinaria (F-1)
     * GET /api/agri/reportes/precios-maquinaria
     */
    public function preciosMaquinaria(Request $request)
    {
        try {
            // Consultar análisis de precios
            $query = AgriAnalisisPrecio::tipoFormulario('F-1');

            // Filtrar por año si se proporciona
            if ($request->ano) {
                $query->whereYear('fecha', $request->ano);
            }

            // Filtrar por mes si se proporciona
            if ($request->mes) {
                $query->whereMonth('fecha', $request->mes);
            }

            // Filtrar por rango de fechas solo si NO se proporcionan ano/mes
            if (!$request->ano && !$request->mes) {
                $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
                $fecha_fin = $request->fecha_fin ?? now()->toDateString();
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            } elseif ($request->fecha_inicio && $request->fecha_fin) {
                // Si se proporcionan fechas específicas, usarlas además de ano/mes
                $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
            }

            // Filtrar por provincia
            if ($request->provincia) {
                $query->provincia($request->provincia);
            }

            // Filtrar por tipo_maquinaria_id
            if ($request->tipo_maquinaria_id) {
                $query->where('producto_id', $request->tipo_maquinaria_id);
            }

            // Filtrar por región si se proporciona
            if ($request->region) {
                $query->where('region', $request->region);
            }

            // Filtrar por distrito si se proporciona
            if ($request->distrito) {
                $query->where('distrito', $request->distrito);
            }

            // Filtrar por tipo/categoría si se proporciona
            if ($request->tipo) {
                $query->where('producto_categoria', $request->tipo);
            }

            $analisis = $query->orderBy('fecha', 'desc')
                ->orderBy('producto_nombre')
                ->get();

            // Agrupar por producto
            $precios_por_tipo = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'tipo_maquinaria_id' => $first->producto_id,
                    'nombre' => $first->producto_nombre,
                    'categoria' => $first->producto_categoria,
                    'estadisticas' => [
                        'precio_promedio' => round($items->avg('precio_promedio'), 2),
                        'precio_minimo' => $items->min('precio_minimo'),
                        'precio_maximo' => $items->max('precio_maximo'),
                        'num_registros' => $items->sum('num_registros'),
                        'num_encuestas' => $items->sum('num_encuestas'),
                    ],
                    'tendencia' => [
                        'cambio_porcentual' => round($items->avg('variacion_porcentual'), 2),
                        'direccion' => $items->first()->tendencia,
                    ]
                ];
            })->values();

            // Comparativo por categoría
            $comparativo_por_categoria = $analisis->groupBy('producto_categoria')->map(function($items, $categoria) {
                return [
                    'categoria' => $categoria,
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'num_tipos' => $items->unique('producto_nombre')->count(),
                ];
            })->values();

            $resumen = [
                'total_encuestas' => $analisis->sum('num_encuestas'),
                'total_registros' => $analisis->sum('num_registros'),
                'tipos_distintos' => $analisis->unique('producto_nombre')->count(),
            ];

            // Construir información del periodo
            $periodo = [];
            if ($request->ano) {
                $periodo['ano'] = $request->ano;
            }
            if ($request->mes) {
                $periodo['mes'] = $request->mes;
            }
            if ($request->fecha_inicio) {
                $periodo['fecha_inicio'] = $request->fecha_inicio;
            }
            if ($request->fecha_fin) {
                $periodo['fecha_fin'] = $request->fecha_fin;
            }

            return response()->json([
                'periodo' => $periodo,
                'resumen' => $resumen,
                'precios_por_tipo' => $precios_por_tipo,
                'comparativo_por_categoria' => $comparativo_por_categoria,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de maquinaria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reporte de precios de fertilizantes (F-4)
     * GET /api/agri/reportes/precios-fertilizantes
     */
    public function preciosFertilizantes(Request $request)
    {
        try {
            // Consultar análisis de precios
            $query = AgriAnalisisPrecio::tipoFormulario('F-4');

            // Filtrar por año si se proporciona
            if ($request->ano) {
                $query->whereYear('fecha', $request->ano);
            }

            // Filtrar por mes si se proporciona
            if ($request->mes) {
                $query->whereMonth('fecha', $request->mes);
            }

            // Filtrar por rango de fechas solo si NO se proporcionan ano/mes
            if (!$request->ano && !$request->mes) {
                $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
                $fecha_fin = $request->fecha_fin ?? now()->toDateString();
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            } elseif ($request->fecha_inicio && $request->fecha_fin) {
                // Si se proporcionan fechas específicas, usarlas además de ano/mes
                $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
            }

            // Filtrar por provincia
            if ($request->provincia) {
                $query->provincia($request->provincia);
            }

            // Filtrar por tipo (categoría)
            if ($request->tipo) {
                $query->where('producto_categoria', $request->tipo);
            }

            // Filtrar por región si se proporciona
            if ($request->region) {
                $query->where('region', $request->region);
            }

            // Filtrar por distrito si se proporciona
            if ($request->distrito) {
                $query->where('distrito', $request->distrito);
            }

            // Filtrar por nombre de producto si se proporciona
            if ($request->producto) {
                $query->where('producto_nombre', 'like', '%' . $request->producto . '%');
            }

            $analisis = $query->orderBy('fecha', 'desc')->get();

            // Agrupar por fertilizante
            $precios_por_fertilizante = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'fertilizante_id' => $first->producto_id,
                    'nombre_comercial' => $first->producto_nombre,
                    'tipo' => $first->producto_categoria,
                    'estadisticas' => [
                        'precio_promedio' => round($items->avg('precio_promedio'), 2),
                        'precio_minimo' => $items->min('precio_minimo'),
                        'precio_maximo' => $items->max('precio_maximo'),
                        'num_registros' => $items->sum('num_registros'),
                        'num_casas_comerciales' => $items->max('num_casas_comerciales'),
                    ],
                    'tendencia' => [
                        'cambio_porcentual' => round($items->avg('variacion_porcentual'), 2),
                        'direccion' => $items->first()->tendencia,
                    ]
                ];
            })->values();

            // Comparativo por tipo
            $comparativo_por_tipo = $analisis->groupBy('producto_categoria')->map(function($items, $tipo) {
                return [
                    'tipo' => $tipo,
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'num_productos' => $items->unique('producto_nombre')->count(),
                ];
            })->values();

            $resumen = [
                'total_encuestas' => $analisis->sum('num_encuestas'),
                'total_registros' => $analisis->sum('num_registros'),
                'fertilizantes_distintos' => $analisis->unique('producto_nombre')->count(),
            ];

            // Construir información del periodo
            $periodo = [];
            if ($request->ano) {
                $periodo['ano'] = $request->ano;
            }
            if ($request->mes) {
                $periodo['mes'] = $request->mes;
            }
            if ($request->fecha_inicio) {
                $periodo['fecha_inicio'] = $request->fecha_inicio;
            }
            if ($request->fecha_fin) {
                $periodo['fecha_fin'] = $request->fecha_fin;
            }

            return response()->json([
                'periodo' => $periodo,
                'resumen' => $resumen,
                'precios_por_fertilizante' => $precios_por_fertilizante,
                'comparativo_por_tipo' => $comparativo_por_tipo,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de fertilizantes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reporte de precios de agroquímicos (F-6)
     * GET /api/agri/reportes/precios-agroquimicos
     */
    public function preciosAgroquimicos(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
            $fecha_fin = $request->fecha_fin ?? now()->toDateString();
            $provincia = $request->provincia;
            $categoria = $request->categoria; // Herbicida, Insecticida, Fungicida, etc.
            $principio_activo = $request->principio_activo;

            $query = AgriAnalisisPrecio::tipoFormulario('F-6')
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);

            if ($provincia) {
                $query->provincia($provincia);
            }

            if ($categoria) {
                $query->where('producto_categoria', $categoria);
            }

            if ($principio_activo) {
                $query->where(function($q) use ($principio_activo) {
                    $q->where('producto_nombre', 'LIKE', "%{$principio_activo}%")
                      ->orWhere('producto_categoria', 'LIKE', "%{$principio_activo}%");
                });
            }

            $analisis = $query->orderBy('fecha', 'desc')->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos disponibles para el período seleccionado',
                    'periodo' => [
                        'fecha_inicio' => $fecha_inicio,
                        'fecha_fin' => $fecha_fin,
                    ]
                ], 404);
            }

            // Agrupar por agroquímico
            $precios_por_agroquimico = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'agroquimico_id' => $first->producto_id,
                    'nombre_comercial' => $first->producto_nombre,
                    'categoria' => $first->producto_categoria,
                    'estadisticas' => [
                        'precio_promedio' => round($items->avg('precio_promedio'), 2),
                        'precio_minimo' => $items->min('precio_minimo'),
                        'precio_maximo' => $items->max('precio_maximo'),
                        'desviacion_estandar' => round($items->avg('desviacion_estandar'), 2),
                        'volatilidad' => $first->volatilidad,
                        'num_registros' => $items->sum('num_registros'),
                        'num_casas_comerciales' => $items->max('num_casas_comerciales'),
                    ],
                    'ubicaciones' => [
                        'regiones' => $items->pluck('region')->unique()->values(),
                        'provincias' => $items->pluck('provincia')->unique()->values(),
                    ],
                    'tendencia' => [
                        'cambio_porcentual' => round($items->avg('variacion_porcentual'), 2),
                        'direccion' => $items->first()->tendencia,
                        'variacion_texto' => $items->first()->variacion_texto,
                    ]
                ];
            })->values();

            // Comparativo por categoría
            $comparativo_por_categoria = $analisis->groupBy('producto_categoria')->map(function($items, $categoria) {
                return [
                    'categoria' => $categoria,
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'precio_minimo' => $items->min('precio_minimo'),
                    'precio_maximo' => $items->max('precio_maximo'),
                    'num_productos' => $items->unique('producto_nombre')->count(),
                    'tendencia_general' => $items->groupBy('tendencia')->map->count()->sortDesc()->keys()->first(),
                ];
            })->values();

            // Análisis de volatilidad
            $analisis_volatilidad = $analisis->groupBy('volatilidad')->map(function($items, $volatilidad) {
                return [
                    'nivel' => $volatilidad,
                    'cantidad_productos' => $items->unique('producto_nombre')->count(),
                    'porcentaje' => 0, // Se calculará después
                ];
            });

            $total_productos = $analisis->unique('producto_nombre')->count();
            if ($total_productos > 0) {
                $analisis_volatilidad = $analisis_volatilidad->map(function($item) use ($total_productos) {
                    $item['porcentaje'] = round(($item['cantidad_productos'] / $total_productos) * 100, 2);
                    return $item;
                });
            }

            $resumen = [
                'total_encuestas' => $analisis->sum('num_encuestas'),
                'total_registros' => $analisis->sum('num_registros'),
                'agroquimicos_distintos' => $total_productos,
                'categorias' => $analisis->unique('producto_categoria')->count(),
                'provincias_cubiertas' => $analisis->unique('provincia')->count(),
            ];

            return response()->json([
                'periodo' => [
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                ],
                'resumen' => $resumen,
                'precios_por_agroquimico' => $precios_por_agroquimico,
                'comparativo_por_categoria' => $comparativo_por_categoria,
                'analisis_volatilidad' => $analisis_volatilidad->values(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de agroquímicos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reporte de precios de transporte (F-14)
     * GET /api/agri/reportes/precios-transporte
     */
    public function preciosTransporte(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
            $fecha_fin = $request->fecha_fin ?? now()->toDateString();
            $provincia = $request->provincia;
            $tipo_vehiculo = $request->tipo_vehiculo;
            $ruta = $request->ruta;

            $query = AgriAnalisisPrecio::tipoFormulario('F-14')
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);

            if ($provincia) {
                $query->provincia($provincia);
            }

            if ($tipo_vehiculo) {
                $query->where('producto_categoria', $tipo_vehiculo);
            }

            if ($ruta) {
                $query->where('producto_nombre', 'LIKE', "%{$ruta}%");
            }

            $analisis = $query->orderBy('fecha', 'desc')->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos de transporte disponibles para el período seleccionado',
                    'periodo' => [
                        'fecha_inicio' => $fecha_inicio,
                        'fecha_fin' => $fecha_fin,
                    ]
                ], 404);
            }

            // Agrupar por ruta/destino
            $precios_por_ruta = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'ruta_id' => $first->producto_id,
                    'ruta' => $first->producto_nombre,
                    'tipo_vehiculo' => $first->producto_categoria,
                    'estadisticas' => [
                        'precio_promedio_viaje' => round($items->avg('precio_promedio'), 2),
                        'precio_minimo' => $items->min('precio_minimo'),
                        'precio_maximo' => $items->max('precio_maximo'),
                        'desviacion_estandar' => round($items->avg('desviacion_estandar'), 2),
                        'num_registros' => $items->sum('num_registros'),
                        'num_transportistas' => $items->max('num_transportistas') ?? 0,
                    ],
                    'cobertura' => [
                        'regiones' => $items->pluck('region')->unique()->values(),
                        'provincias' => $items->pluck('provincia')->unique()->values(),
                        'distritos' => $items->pluck('distrito')->unique()->values(),
                    ],
                    'tendencia' => [
                        'cambio_porcentual' => round($items->avg('variacion_porcentual'), 2),
                        'direccion' => $items->first()->tendencia,
                        'observacion' => $this->obtenerObservacionTendencia($items->first()->tendencia, $items->avg('variacion_porcentual')),
                    ]
                ];
            })->values();

            // Comparativo por tipo de vehículo
            $comparativo_por_vehiculo = $analisis->groupBy('producto_categoria')->map(function($items, $tipo) {
                return [
                    'tipo_vehiculo' => $tipo,
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'num_rutas' => $items->unique('producto_nombre')->count(),
                    'tendencia_predominante' => $items->groupBy('tendencia')->map->count()->sortDesc()->keys()->first(),
                ];
            })->values();

            // Análisis por distancia (si está disponible en los datos)
            $analisis_costo_distancia = [
                'costo_promedio_por_km' => $this->calcularCostoPromedioPorKm($analisis),
                'costo_promedio_por_tonelada' => $this->calcularCostoPromedioPorTonelada($analisis),
            ];

            // Rutas más económicas y más costosas
            $rutas_economicas = $precios_por_ruta->sortBy('estadisticas.precio_promedio_viaje')->take(5)->values();
            $rutas_costosas = $precios_por_ruta->sortByDesc('estadisticas.precio_promedio_viaje')->take(5)->values();

            $resumen = [
                'total_encuestas' => $analisis->sum('num_encuestas'),
                'total_registros' => $analisis->sum('num_registros'),
                'rutas_distintas' => $analisis->unique('producto_nombre')->count(),
                'tipos_vehiculo' => $analisis->unique('producto_categoria')->count(),
                'provincias_cubiertas' => $analisis->unique('provincia')->count(),
                'precio_promedio_general' => round($analisis->avg('precio_promedio'), 2),
            ];

            return response()->json([
                'periodo' => [
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                ],
                'resumen' => $resumen,
                'precios_por_ruta' => $precios_por_ruta,
                'comparativo_por_vehiculo' => $comparativo_por_vehiculo,
                'analisis_costo_distancia' => $analisis_costo_distancia,
                'ranking' => [
                    'rutas_mas_economicas' => $rutas_economicas,
                    'rutas_mas_costosas' => $rutas_costosas,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de transporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Análisis de tendencias
     * GET /api/agri/reportes/tendencias
     */
    public function tendencias(Request $request)
    {
        try {
            $tipo_formulario = $request->tipo_formulario ?? 'F-4';
            $producto_id = $request->producto_id;
            $fecha_inicio = $request->fecha_inicio ?? now()->subMonths(6)->startOfMonth()->toDateString();
            $fecha_fin = $request->fecha_fin ?? now()->toDateString();

            $query = AgriAnalisisPrecio::tipoFormulario($tipo_formulario)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->orderBy('fecha');

            if ($producto_id) {
                $query->where('producto_id', $producto_id);
            }

            $serie_temporal = $query->get();

            if ($serie_temporal->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos suficientes para análisis de tendencias'
                ], 404);
            }

            // Producto info
            $producto = $serie_temporal->first();

            // Estadísticas de tendencia
            $precio_inicial = $serie_temporal->first()->precio_promedio;
            $precio_final = $serie_temporal->last()->precio_promedio;
            $variacion_total = $precio_final - $precio_inicial;
            $variacion_porcentual = $precio_inicial > 0 ? ($variacion_total / $precio_inicial) * 100 : 0;

            // Calcular tendencia
            $aumentos = $serie_temporal->where('tendencia', 'aumento')->count();
            $disminuciones = $serie_temporal->where('tendencia', 'disminución')->count();
            $tendencia_general = $aumentos > $disminuciones ? 'creciente' : ($disminuciones > $aumentos ? 'decreciente' : 'estable');

            return response()->json([
                'producto' => [
                    'id' => $producto->producto_id,
                    'nombre' => $producto->producto_nombre,
                    'categoria' => $producto->producto_categoria,
                ],
                'periodo_analisis' => [
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'meses' => $serie_temporal->unique('mes')->count(),
                ],
                'serie_temporal' => $serie_temporal->map(function($item) {
                    return [
                        'periodo' => $item->fecha->format('Y-m'),
                        'fecha' => $item->fecha->format('Y-m-d'),
                        'precio_promedio' => $item->precio_promedio,
                        'precio_minimo' => $item->precio_minimo,
                        'precio_maximo' => $item->precio_maximo,
                        'num_registros' => $item->num_registros,
                    ];
                }),
                'estadisticas_tendencia' => [
                    'precio_inicial' => $precio_inicial,
                    'precio_final' => $precio_final,
                    'variacion_total_absoluta' => round($variacion_total, 2),
                    'variacion_total_porcentual' => round($variacion_porcentual, 2),
                    'desviacion_estandar' => round($serie_temporal->avg('desviacion_estandar'), 2),
                    'volatilidad' => $serie_temporal->first()->volatilidad ?? 'media',
                ],
                'tendencia' => $tendencia_general,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar análisis de tendencias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Productividad de encuestadores
     * GET /api/agri/reportes/productividad-encuestadores
     */
    public function productividadEncuestadores(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
            $fecha_fin = $request->fecha_fin ?? now()->toDateString();
            $encuestador_id = $request->encuestador_id;

            $query = DB::table('agri_encuestas_insumos as e')
                ->join('agri_encuestadores_insumos as enc', 'e.encuestador_id', '=', 'enc.id')
                ->whereBetween('e.fecha_encuesta', [$fecha_inicio, $fecha_fin])
                ->where('e.deleted_at', null);

            if ($encuestador_id) {
                $query->where('e.encuestador_id', $encuestador_id);
            }

            $encuestadores = $query->select(
                    'enc.id as encuestador_id',
                    'enc.nombres',
                    'enc.apellido_paterno',
                    'enc.apellido_materno',
                    'enc.especializacion',
                    'enc.provincia_asignada',
                    'enc.distrito_asignado',
                    DB::raw('COUNT(*) as total_encuestas'),
                    DB::raw('SUM(CASE WHEN e.estado_validacion = "validado" THEN 1 ELSE 0 END) as encuestas_validadas'),
                    DB::raw('SUM(CASE WHEN e.estado_validacion = "rechazado" THEN 1 ELSE 0 END) as encuestas_rechazadas'),
                    DB::raw('SUM(CASE WHEN e.estado_validacion = "pendiente" THEN 1 ELSE 0 END) as encuestas_pendientes'),
                    DB::raw('COUNT(DISTINCT DATE(e.fecha_encuesta)) as dias_trabajados'),
                    DB::raw('ROUND(COUNT(*) / COUNT(DISTINCT DATE(e.fecha_encuesta)), 2) as promedio_encuestas_dia')
                )
                ->groupBy('enc.id', 'enc.nombres', 'enc.apellido_paterno', 'enc.apellido_materno',
                         'enc.especializacion', 'enc.provincia_asignada', 'enc.distrito_asignado')
                ->orderBy('total_encuestas', 'desc')
                ->get();

            // Obtener metas para cada encuestador
            $encuestadores = $encuestadores->map(function($enc) use ($fecha_inicio, $fecha_fin) {
                $carbon_inicio = Carbon::parse($fecha_inicio);

                $meta = AgriMetaMensual::where('encuestador_id', $enc->encuestador_id)
                    ->where('año', $carbon_inicio->year)
                    ->where('mes', $carbon_inicio->month)
                    ->first();

                $tasa_aprobacion = $enc->total_encuestas > 0
                    ? round(($enc->encuestas_validadas / $enc->total_encuestas) * 100, 2)
                    : 0;

                return [
                    'encuestador_id' => $enc->encuestador_id,
                    'nombres' => $enc->nombres . ' ' . $enc->apellido_paterno,
                    'especializacion' => explode(',', $enc->especializacion),
                    'provincia_asignada' => $enc->provincia_asignada,
                    'estadisticas' => [
                        'total_encuestas' => $enc->total_encuestas,
                        'encuestas_validadas' => $enc->encuestas_validadas,
                        'encuestas_rechazadas' => $enc->encuestas_rechazadas,
                        'encuestas_pendientes' => $enc->encuestas_pendientes,
                        'tasa_aprobacion' => $tasa_aprobacion,
                        'dias_trabajados' => $enc->dias_trabajados,
                        'promedio_encuestas_dia' => $enc->promedio_encuestas_dia,
                    ],
                    'cumplimiento_metas' => $meta ? [
                        'meta_mensual' => $meta->meta_total,
                        'logrado' => $meta->logrado_total,
                        'porcentaje_cumplimiento' => $meta->porcentaje_total,
                        'estado' => strtoupper($meta->estado),
                    ] : null,
                ];
            });

            // Ranking
            $ranking = $encuestadores->sortByDesc('estadisticas.total_encuestas')
                ->take(10)
                ->values()
                ->map(function($enc, $index) {
                    return [
                        'posicion' => $index + 1,
                        'encuestador' => $enc['nombres'],
                        'total_encuestas' => $enc['estadisticas']['total_encuestas'],
                        'tasa_aprobacion' => $enc['estadisticas']['tasa_aprobacion'],
                    ];
                });

            return response()->json([
                'periodo' => [
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                ],
                'encuestadores' => $encuestadores->values(),
                'ranking' => $ranking,
                'totales' => [
                    'total_encuestadores' => $encuestadores->count(),
                    'total_encuestas' => $encuestadores->sum('estadisticas.total_encuestas'),
                    'promedio_por_encuestador' => $encuestadores->count() > 0
                        ? round($encuestadores->sum('estadisticas.total_encuestas') / $encuestadores->count(), 2)
                        : 0,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de productividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cumplimiento de metas
     * GET /api/agri/reportes/cumplimiento-metas
     */
    public function cumplimientoMetas(Request $request)
    {
        try {
            $año = $request->año ?? now()->year;
            $mes = $request->mes ?? now()->month;

            $metas = AgriMetaMensual::periodo($año, $mes)
                ->with(['encuestador', 'supervisor'])
                ->get();

            if ($metas->isEmpty()) {
                return response()->json([
                    'message' => 'No hay metas definidas para este período',
                    'periodo' => ['año' => $año, 'mes' => $mes]
                ], 404);
            }

            // Meta regional
            $meta_regional = $metas->where('aplica_a', 'regional')->first();

            // Metas por tipo de formulario
            $por_tipo_formulario = [
                [
                    'tipo' => 'F-1',
                    'nombre' => 'Maquinaria',
                    'meta' => $meta_regional->meta_f1 ?? 0,
                    'realizado' => $meta_regional->logrado_f1 ?? 0,
                    'cumplimiento' => $meta_regional->porcentaje_f1 ?? 0,
                    'estado' => $this->determinarEstado($meta_regional->porcentaje_f1 ?? 0),
                ],
                [
                    'tipo' => 'F-4',
                    'nombre' => 'Fertilizantes',
                    'meta' => $meta_regional->meta_f4 ?? 0,
                    'realizado' => $meta_regional->logrado_f4 ?? 0,
                    'cumplimiento' => $meta_regional->porcentaje_f4 ?? 0,
                    'estado' => $this->determinarEstado($meta_regional->porcentaje_f4 ?? 0),
                ],
                [
                    'tipo' => 'F-6',
                    'nombre' => 'Agroquímicos',
                    'meta' => $meta_regional->meta_f6 ?? 0,
                    'realizado' => $meta_regional->logrado_f6 ?? 0,
                    'cumplimiento' => $meta_regional->porcentaje_f6 ?? 0,
                    'estado' => $this->determinarEstado($meta_regional->porcentaje_f6 ?? 0),
                ],
                [
                    'tipo' => 'F-14',
                    'nombre' => 'Transporte',
                    'meta' => $meta_regional->meta_f14 ?? 0,
                    'realizado' => $meta_regional->logrado_f14 ?? 0,
                    'cumplimiento' => $meta_regional->porcentaje_f14 ?? 0,
                    'estado' => $this->determinarEstado($meta_regional->porcentaje_f14 ?? 0),
                ],
            ];

            // Metas por provincia
            $por_provincia = $metas->where('aplica_a', 'provincia')
                ->map(function($meta) {
                    return [
                        'provincia' => $meta->provincia,
                        'meta' => $meta->meta_total,
                        'realizado' => $meta->logrado_total,
                        'cumplimiento' => $meta->porcentaje_total,
                    ];
                })
                ->values();

            return response()->json([
                'periodo' => [
                    'año' => $año,
                    'mes' => $mes,
                    'mes_nombre' => Carbon::create($año, $mes)->locale('es')->monthName,
                ],
                'metas_generales' => [
                    'meta_encuestas_mes' => $meta_regional->meta_total ?? 0,
                    'encuestas_realizadas' => $meta_regional->logrado_total ?? 0,
                    'porcentaje_cumplimiento' => $meta_regional->porcentaje_total ?? 0,
                    'estado' => strtoupper($meta_regional->estado ?? 'PENDIENTE'),
                ],
                'por_tipo_formulario' => $por_tipo_formulario,
                'por_provincia' => $por_provincia,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de cumplimiento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard general
     * GET /api/agri/reportes/dashboard-general
     */
    public function dashboardGeneral()
    {
        try {
            $hoy = now()->toDateString();
            $mes_actual = now();

            // Métricas generales
            $total_encuestas = DB::table('agri_encuestas_insumos')
                ->whereNull('deleted_at')
                ->count();

            $encuestas_mes = DB::table('agri_encuestas_insumos')
                ->whereYear('fecha_recoleccion', $mes_actual->year)
                ->whereMonth('fecha_recoleccion', $mes_actual->month)
                ->whereNull('deleted_at')
                ->count();

            $estadisticas_validacion = DB::table('agri_encuestas_insumos')
                ->whereYear('fecha_recoleccion', $mes_actual->year)
                ->whereMonth('fecha_recoleccion', $mes_actual->month)
                ->whereNull('deleted_at')
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = "validado" THEN 1 ELSE 0 END) as validadas,
                    SUM(CASE WHEN estado = "enviado" THEN 1 ELSE 0 END) as pendientes,
                    SUM(CASE WHEN estado = "rechazado" THEN 1 ELSE 0 END) as rechazadas
                ')
                ->first();

            // Personal
            $personal = [
                'encuestadores_activos' => DB::table('agri_encuestadores_insumos')
                    ->where('estado', 'activo')
                    ->whereNull('deleted_at')
                    ->count(),
                'supervisores_activos' => DB::table('agri_supervisores_insumos')
                    ->where('estado', 'activo')
                    ->whereNull('deleted_at')
                    ->count(),
            ];

            // Meta del mes
            $meta_mes = AgriMetaMensual::periodo($mes_actual->year, $mes_actual->month)
                ->where('aplica_a', 'regional')
                ->first();

            // Tendencias de precios
            $tendencias = DB::table('agri_analisis_precios')
                ->selectRaw('
                    SUM(CASE WHEN tendencia = "aumento" THEN 1 ELSE 0 END) as aumentos,
                    SUM(CASE WHEN tendencia = "disminución" THEN 1 ELSE 0 END) as disminuciones,
                    SUM(CASE WHEN tendencia = "estable" THEN 1 ELSE 0 END) as estables
                ')
                ->first();

            return response()->json([
                'fecha_actualizacion' => now()->format('Y-m-d H:i:s'),
                'estado_sistema' => 'OPERATIVO',
                'metricas_generales' => [
                    'total_encuestas' => $total_encuestas,
                    'encuestas_mes_actual' => $encuestas_mes,
                    'encuestas_validadas' => $estadisticas_validacion->validadas ?? 0,
                    'encuestas_pendientes' => $estadisticas_validacion->pendientes ?? 0,
                    'tasa_validacion_general' => $encuestas_mes > 0
                        ? round(($estadisticas_validacion->validadas / $encuestas_mes) * 100, 2)
                        : 0,
                ],
                'personal' => $personal,
                'catalogo_productos' => [
                    'tipos_maquinaria' => DB::table('agri_tipo_maquinaria_insumos')->count(),
                    'fertilizantes' => DB::table('agri_fertilizantes_insumos')->count(),
                    'agroquimicos' => DB::table('agri_agroquimicos_insumos')->count(),
                ],
                'meta_mes_actual' => $meta_mes ? [
                    'meta_total' => $meta_mes->meta_total,
                    'logrado' => $meta_mes->logrado_total,
                    'porcentaje' => $meta_mes->porcentaje_total,
                    'estado' => strtoupper($meta_mes->estado),
                ] : null,
                'tendencias_precios' => [
                    'productos_con_aumento' => $tendencias->aumentos ?? 0,
                    'productos_con_disminucion' => $tendencias->disminuciones ?? 0,
                    'productos_estables' => $tendencias->estables ?? 0,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar dashboard general',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper para determinar estado de cumplimiento
     */
    private function determinarEstado($porcentaje)
    {
        if ($porcentaje >= 100) {
            return 'CUMPLIDO';
        } elseif ($porcentaje >= 90) {
            return 'CASI CUMPLIDO';
        } else {
            return 'EN PROGRESO';
        }
    }

    /**
     * Helper para obtener observación de tendencia
     */
    private function obtenerObservacionTendencia($tendencia, $variacion)
    {
        $variacion = round($variacion, 2);

        if ($tendencia === 'aumento') {
            if ($variacion > 10) {
                return "Incremento significativo de {$variacion}% - Requiere atención";
            } elseif ($variacion > 5) {
                return "Incremento moderado de {$variacion}%";
            } else {
                return "Ligero incremento de {$variacion}%";
            }
        } elseif ($tendencia === 'disminución') {
            $variacion_abs = abs($variacion);
            if ($variacion_abs > 10) {
                return "Disminución significativa de {$variacion_abs}%";
            } elseif ($variacion_abs > 5) {
                return "Disminución moderada de {$variacion_abs}%";
            } else {
                return "Ligera disminución de {$variacion_abs}%";
            }
        } else {
            return "Precio estable (variación menor a 2%)";
        }
    }

    /**
     * Helper para calcular costo promedio por km
     */
    private function calcularCostoPromedioPorKm($analisis)
    {
        // Este cálculo depende de si tienes datos de distancia en tus registros
        // Por ahora retornamos un estimado basado en el precio promedio
        // Ajusta según tu estructura de datos real

        $precio_promedio = $analisis->avg('precio_promedio');
        $distancia_estimada = 100; // km promedio estimado

        if ($precio_promedio > 0 && $distancia_estimada > 0) {
            return round($precio_promedio / $distancia_estimada, 2);
        }

        return null;
    }

    /**
     * Helper para calcular costo promedio por tonelada
     */
    private function calcularCostoPromedioPorTonelada($analisis)
    {
        // Este cálculo depende de si tienes datos de capacidad de carga
        // Por ahora retornamos un estimado basado en el precio promedio
        // Ajusta según tu estructura de datos real

        $precio_promedio = $analisis->avg('precio_promedio');
        $capacidad_promedio = 10; // toneladas promedio estimadas

        if ($precio_promedio > 0 && $capacidad_promedio > 0) {
            return round($precio_promedio / $capacidad_promedio, 2);
        }

        return null;
    }

    /**
     * Exportar análisis de precios a Excel
     * GET /api/agri/reportes/analisis-precios-export
     */
    public function analisisPreciosExport(Request $request)
    {
        try {
            $tipo_formulario = $request->tipo_formulario ?? 'F-6'; // F-6 o F-14
            $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
            $fecha_fin = $request->fecha_fin ?? now()->toDateString();

            // Construir query
            $query = AgriAnalisisPrecio::tipoFormulario($tipo_formulario)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->orderBy('fecha', 'desc');

            // Aplicar filtros opcionales
            if ($request->region) {
                $query->where('region', $request->region);
            }
            if ($request->provincia) {
                $query->where('provincia', $request->provincia);
            }
            if ($request->producto) {
                $query->where('producto_nombre', 'LIKE', "%{$request->producto}%");
            }
            if ($request->categoria && $tipo_formulario == 'F-6') {
                $query->where('producto_categoria', $request->categoria);
            }

            $datos = $query->get();

            if ($datos->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos disponibles para exportar'
                ], 404);
            }

            // Calcular estadísticas
            $estadisticas = [
                'total_registros' => $datos->count(),
                'precio_promedio_general' => round($datos->avg('precio_promedio'), 2),
                'precio_minimo_general' => $datos->min('precio_minimo'),
                'precio_maximo_general' => $datos->max('precio_maximo'),
                'productos_distintos' => $datos->unique('producto_nombre')->count(),
            ];

            // Preparar información de filtros para el reporte
            $filtros = [
                'periodo' => Carbon::parse($fecha_inicio)->format('d/m/Y') . ' - ' . Carbon::parse($fecha_fin)->format('d/m/Y'),
                'region' => $request->region ?? 'Todas',
                'provincia' => $request->provincia ?? 'Todas',
                'distrito' => $request->distrito ?? 'Todos',
                'año' => Carbon::parse($fecha_inicio)->year,
                'mes' => Carbon::parse($fecha_inicio)->month,
                'mes_nombre' => Carbon::parse($fecha_inicio)->locale('es')->monthName,
            ];

            $nombreArchivo = 'analisis_precios_' . $tipo_formulario . '_' . date('Y-m-d_His') . '.xlsx';

            // Usar exportador específico para F-6
            if ($tipo_formulario === 'F-6') {
                return Excel::download(
                    new AgroquimicosF6Export($datos, $filtros, $estadisticas),
                    $nombreArchivo
                );
            }

            // Usar exportador genérico para otros formularios
            return Excel::download(
                new AnalisisPreciosExport($datos, $tipo_formulario, $filtros),
                $nombreArchivo
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al exportar análisis de precios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar análisis de precios a PDF
     * GET /api/agri/reportes/analisis-precios-pdf
     */
    public function analisisPreciosPdf(Request $request)
    {
        try {
            $tipo_formulario = $request->tipo_formulario ?? 'F-6';
            $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
            $fecha_fin = $request->fecha_fin ?? now()->toDateString();

            // Construir query
            $query = AgriAnalisisPrecio::tipoFormulario($tipo_formulario)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->orderBy('fecha', 'desc');

            // Aplicar filtros
            if ($request->region) {
                $query->where('region', $request->region);
            }
            if ($request->provincia) {
                $query->where('provincia', $request->provincia);
            }
            if ($request->producto) {
                $query->where('producto_nombre', 'LIKE', "%{$request->producto}%");
            }

            $datos = $query->get();

            if ($datos->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos disponibles para generar PDF'
                ], 404);
            }

            // Calcular estadísticas
            $estadisticas = [
                'total_registros' => $datos->count(),
                'precio_promedio_general' => round($datos->avg('precio_promedio'), 2),
                'precio_minimo_general' => $datos->min('precio_minimo'),
                'precio_maximo_general' => $datos->max('precio_maximo'),
                'productos_distintos' => $datos->unique('producto_nombre')->count(),
            ];

            $filtros = [
                'periodo' => Carbon::parse($fecha_inicio)->format('d/m/Y') . ' - ' . Carbon::parse($fecha_fin)->format('d/m/Y'),
                'tipo_formulario' => $tipo_formulario,
                'tipo_formulario_nombre' => $tipo_formulario === 'F-6' ? 'Agroquímicos' : 'Transporte',
                'region' => $request->region ?? 'Todas las regiones',
                'provincia' => $request->provincia ?? 'Todas las provincias',
                'distrito' => $request->distrito ?? 'Todos los distritos',
                'año' => Carbon::parse($fecha_inicio)->year,
                'mes' => Carbon::parse($fecha_inicio)->month,
                'mes_nombre' => Carbon::parse($fecha_inicio)->locale('es')->monthName,
            ];

            // Usar vista específica para F-6
            $vistaPath = $tipo_formulario === 'F-6' ? 'pdf.agroquimicos-f6' : 'pdf.analisis-precios';

            $pdf = Pdf::loadView($vistaPath, [
                'datos' => $datos,
                'filtros' => $filtros,
                'estadisticas' => $estadisticas,
                'fecha_generacion' => now()->format('d/m/Y H:i'),
            ]);

            $pdf->setPaper('A4', 'landscape');

            $nombreArchivo = 'analisis_precios_' . $tipo_formulario . '_' . date('Y-m-d') . '.pdf';

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar reporte de transporte a Excel
     * GET /api/agri/reportes/transporte-export
     */
    public function transporteExport(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio ?? now()->startOfMonth()->toDateString();
            $fecha_fin = $request->fecha_fin ?? now()->toDateString();

            $query = AgriAnalisisPrecio::tipoFormulario('F-14')
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                ->orderBy('fecha', 'desc');

            // Aplicar filtros
            if ($request->tipo_vehiculo) {
                $query->where('producto_categoria', $request->tipo_vehiculo);
            }
            if ($request->origen) {
                $query->where('producto_nombre', 'LIKE', "%{$request->origen}%");
            }
            if ($request->destino) {
                $query->where('producto_nombre', 'LIKE', "%{$request->destino}%");
            }
            if ($request->provincia) {
                $query->where('provincia', $request->provincia);
            }

            $datos = $query->get();

            if ($datos->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos de transporte disponibles para exportar'
                ], 404);
            }

            // Calcular estadísticas
            $estadisticas = [
                'total_registros' => $datos->count(),
                'precio_promedio_general' => round($datos->avg('precio_promedio'), 2),
                'precio_minimo_general' => $datos->min('precio_minimo'),
                'precio_maximo_general' => $datos->max('precio_maximo'),
                'rutas_distintas' => $datos->unique('producto_nombre')->count(),
            ];

            // Obtener datos de encuestador y supervisor
            $ano = Carbon::parse($fecha_inicio)->year;
            $mes = Carbon::parse($fecha_inicio)->month;

            $encuestaInfo = DB::table('agri_encuestas_insumos as e')
                ->leftJoin('agri_encuestadores_insumos as enc', 'e.encuestador_id', '=', 'enc.id')
                ->leftJoin('agri_supervisores_insumos as sup', 'e.supervisor_id', '=', 'sup.id')
                ->where('e.tipo_formulario', 'F-14')
                ->where('e.estado', 'validado')
                ->where('e.anio', $ano)
                ->where('e.mes', $mes)
                ->select([
                    'enc.nombres as encuestador_nombre',
                    DB::raw("CONCAT(enc.apellido_paterno, ' ', enc.apellido_materno) as encuestador_apellido"),
                    'enc.cargo as encuestador_cargo',
                    'sup.nombres as supervisor_nombre',
                    DB::raw("CONCAT(sup.apellido_paterno, ' ', sup.apellido_materno) as supervisor_apellido"),
                    'sup.cargo as supervisor_cargo',
                    'e.fecha_validacion'
                ])
                ->first();

            $filtros = [
                'periodo' => Carbon::parse($fecha_inicio)->format('d/m/Y') . ' - ' . Carbon::parse($fecha_fin)->format('d/m/Y'),
                'tipo_vehiculo' => $request->tipo_vehiculo ?? 'Todos',
                'region' => $request->region ?? 'Todas las regiones',
                'provincia' => $request->provincia ?? 'Todas las provincias',
                'distrito' => $request->distrito ?? 'Todos los distritos',
                'año' => $ano,
                'mes' => $mes,
                'mes_nombre' => Carbon::parse($fecha_inicio)->locale('es')->monthName,
                'encuestador_nombre' => $encuestaInfo->encuestador_nombre ?? '',
                'encuestador_apellido' => $encuestaInfo->encuestador_apellido ?? '',
                'encuestador_cargo' => $encuestaInfo->encuestador_cargo ?? '',
                'supervisor_nombre' => $encuestaInfo->supervisor_nombre ?? '',
                'supervisor_apellido' => $encuestaInfo->supervisor_apellido ?? '',
                'supervisor_cargo' => $encuestaInfo->supervisor_cargo ?? '',
                'fecha_validacion' => $encuestaInfo->fecha_validacion ?? null,
            ];

            $nombreArchivo = 'transporte_F14_' . date('Y-m-d_His') . '.xlsx';

            // Usar exportador específico para F-14
            return Excel::download(
                new TransporteF14Export($datos, $filtros, $estadisticas),
                $nombreArchivo
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al exportar reporte de transporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar reporte de transporte a PDF
     * GET /api/agri/reportes/transporte-pdf
     */
    public function transportePdf(Request $request)
    {
        try {
            // Validar parámetros requeridos
            $request->validate([
                'ano' => 'required|integer',
                'mes' => 'required|integer|min:1|max:12',
            ]);

            $ano = $request->ano;
            $mes = $request->mes;

            // Consulta de datos de transporte
            $query = AgriAnalisisPrecio::where('año', $ano)
                ->where('mes', $mes)
                ->where('tipo_formulario', 'F-14')
                ->orderBy('fecha', 'desc');

            // Aplicar filtros adicionales
            if ($request->tipo_vehiculo) {
                $query->where('producto_categoria', $request->tipo_vehiculo);
            }
            if ($request->region) {
                $query->where('region', $request->region);
            }
            if ($request->provincia) {
                $query->where('provincia', $request->provincia);
            }
            if ($request->distrito) {
                $query->where('distrito', $request->distrito);
            }

            $datos = $query->get();

            if ($datos->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos de transporte disponibles para el período seleccionado'
                ], 404);
            }

            // Calcular estadísticas
            $estadisticas = [
                'total_registros' => $datos->count(),
                'precio_promedio_general' => round($datos->avg('precio_promedio'), 2),
                'precio_minimo_general' => $datos->min('precio_minimo'),
                'precio_maximo_general' => $datos->max('precio_maximo'),
                'rutas_distintas' => $datos->unique('producto_nombre')->count(),
            ];

            // Obtener información del encuestador y supervisor
            $encuestasQuery = DB::table('agri_encuestas_insumos as e')
                ->leftJoin('agri_encuestadores_insumos as enc', 'e.encuestador_id', '=', 'enc.id')
                ->leftJoin('agri_supervisores_insumos as sup', 'e.supervisor_id', '=', 'sup.id')
                ->where('e.tipo_formulario', 'F-14')
                ->where('e.estado', 'validado')
                ->where('e.anio', $ano)
                ->where('e.mes', $mes)
                ->select([
                    'enc.nombres as encuestador_nombre',
                    DB::raw("CONCAT(enc.apellido_paterno, ' ', enc.apellido_materno) as encuestador_apellido"),
                    'enc.cargo as encuestador_cargo',
                    'sup.nombres as supervisor_nombre',
                    DB::raw("CONCAT(sup.apellido_paterno, ' ', sup.apellido_materno) as supervisor_apellido"),
                    'sup.cargo as supervisor_cargo',
                    'e.fecha_validacion',
                ])
                ->first();

            // Preparar datos de filtros
            $primerRegistro = $datos->first();
            $filtros = [
                'region' => $request->region ?? $primerRegistro->region ?? 'JUNÍN',
                'provincia' => $request->provincia ?? $primerRegistro->provincia ?? 'HUANCAYO',
                'distrito' => $request->distrito ?? $primerRegistro->distrito ?? 'CHILCA',
                'año' => $ano,
                'mes' => $mes,
                'mes_nombre' => strtoupper(Carbon::createFromDate($ano, $mes, 1)->locale('es')->monthName),
                'encuestador_nombre' => $encuestasQuery->encuestador_nombre ?? '',
                'encuestador_apellido' => $encuestasQuery->encuestador_apellido ?? '',
                'encuestador_cargo' => $encuestasQuery->encuestador_cargo ?? '',
                'supervisor_nombre' => $encuestasQuery->supervisor_nombre ?? '',
                'supervisor_apellido' => $encuestasQuery->supervisor_apellido ?? '',
                'supervisor_cargo' => $encuestasQuery->supervisor_cargo ?? '',
                'fecha_validacion' => $encuestasQuery->fecha_validacion ?? null,
            ];

            // Generar PDF con vista específica F-14
            $pdf = Pdf::loadView('exports.transporte-f14-pdf', [
                'datos' => $datos,
                'filtros' => $filtros,
                'estadisticas' => $estadisticas,
                'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            ]);

            $pdf->setPaper('A4', 'landscape');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);

            $nombreArchivo = 'transporte_F14_' . date('Y-m-d_His') . '.pdf';

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar PDF de transporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar maquinaria a Excel (F-1)
     * GET /api/agri/reportes/maquinaria-export
     */
    public function maquinariaExport(Request $request)
    {
        try {
            // Filtros de fecha
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $mes = $request->mes;
            $ano = $request->ano;

            $query = AgriAnalisisPrecio::tipoFormulario('F-1');

            // Lógica de filtrado (igual que preciosMaquinaria)
            if ($ano || $mes) {
                if ($ano) {
                    $query->whereYear('fecha', $ano);
                }
                if ($mes) {
                    $query->whereMonth('fecha', $mes);
                }
            } else if ($fecha_inicio && $fecha_fin) {
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            } else {
                // Por defecto: mes actual
                $query->whereYear('fecha', now()->year)
                      ->whereMonth('fecha', now()->month);
            }

            // Filtros adicionales
            if ($request->region) {
                $query->where('region', $request->region);
            }
            if ($request->provincia) {
                $query->where('provincia', $request->provincia);
            }
            if ($request->tipo) {
                $query->where('producto_categoria', 'LIKE', "%{$request->tipo}%");
            }

            $analisis = $query->orderBy('producto_nombre')->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos de maquinaria disponibles para exportar'
                ], 404);
            }

            // **AGRUPAR POR PRODUCTO (igual que en preciosMaquinaria)**
            $precios_por_tipo = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'nombre' => $first->producto_nombre,
                    'categoria' => $first->producto_categoria,
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'precio_minimo' => $items->min('precio_minimo'),
                    'precio_maximo' => $items->max('precio_maximo'),
                    'desviacion_estandar' => round($items->avg('desviacion_estandar'), 2),
                    'num_registros' => $items->sum('num_registros'),
                    'num_encuestas' => $items->sum('num_encuestas'),
                ];
            })->values();

            // Obtener primer registro para filtros
            $first = $analisis->first();

            $filtros = [
                'region' => $first->region ?? 'Todas las regiones',
                'provincia' => $first->provincia ?? 'Todas las provincias',
                'distrito' => $first->distrito ?? 'Todos los distritos',
                'anio' => $ano ?? $first->año ?? now()->year,
                'mes' => $mes ?? $first->mes ?? now()->month,
                'mes_nombre' => Carbon::create()->month($mes ?? $first->mes ?? now()->month)->locale('es')->translatedFormat('F'),
            ];

            // Estadísticas generales
            $estadisticas = [
                'total_registros' => $analisis->sum('num_registros'),
                'total_encuestas' => $analisis->sum('num_encuestas'),
                'tipos_distintos' => $precios_por_tipo->count(),
                'precio_promedio' => round($analisis->avg('precio_promedio'), 2),
                'precio_minimo' => $analisis->min('precio_minimo'),
                'precio_maximo' => $analisis->max('precio_maximo'),
            ];

            $nombreArchivo = 'reporte_maquinaria_F1_' . ($filtros['anio']) . '_' . str_pad($filtros['mes'], 2, '0', STR_PAD_LEFT) . '.xlsx';

            return Excel::download(
                new MaquinariaExport($precios_por_tipo, $filtros, $estadisticas),
                $nombreArchivo
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al exportar maquinaria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar maquinaria a PDF (F-1)
     * GET /api/agri/reportes/maquinaria-pdf
     */
    public function maquinariaPdf(Request $request)
    {
        try {
            // Filtros de fecha
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $mes = $request->mes;
            $ano = $request->ano;

            $query = AgriAnalisisPrecio::tipoFormulario('F-1');

            // Lógica de filtrado (igual que maquinariaExport)
            if ($ano || $mes) {
                if ($ano) {
                    $query->whereYear('fecha', $ano);
                }
                if ($mes) {
                    $query->whereMonth('fecha', $mes);
                }
            } else if ($fecha_inicio && $fecha_fin) {
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            } else {
                // Por defecto: mes actual
                $query->whereYear('fecha', now()->year)
                      ->whereMonth('fecha', now()->month);
            }

            // Filtros adicionales
            if ($request->region) {
                $query->where('region', $request->region);
            }
            if ($request->provincia) {
                $query->where('provincia', $request->provincia);
            }
            if ($request->tipo) {
                $query->where('producto_categoria', 'LIKE', "%{$request->tipo}%");
            }

            $analisis = $query->orderBy('producto_nombre')->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos de maquinaria disponibles para generar PDF'
                ], 404);
            }

            // **AGRUPAR POR PRODUCTO (igual que en maquinariaExport)**
            $precios_por_tipo = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'nombre' => $first->producto_nombre,
                    'categoria' => $first->producto_categoria,
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'precio_minimo' => $items->min('precio_minimo'),
                    'precio_maximo' => $items->max('precio_maximo'),
                    'desviacion_estandar' => round($items->avg('desviacion_estandar'), 2),
                    'num_registros' => $items->sum('num_registros'),
                    'num_encuestas' => $items->sum('num_encuestas'),
                ];
            })->values();

            // Obtener primer registro para filtros
            $first = $analisis->first();

            $filtros = [
                'region' => $first->region ?? 'Todas las regiones',
                'provincia' => $first->provincia ?? 'Todas las provincias',
                'distrito' => $first->distrito ?? 'Todos los distritos',
                'anio' => $ano ?? $first->año ?? now()->year,
                'mes' => $mes ?? $first->mes ?? now()->month,
                'mes_nombre' => Carbon::create()->month($mes ?? $first->mes ?? now()->month)->locale('es')->translatedFormat('F'),
            ];

            // Estadísticas generales
            $estadisticas = [
                'total_registros' => $analisis->sum('num_registros'),
                'total_encuestas' => $analisis->sum('num_encuestas'),
                'tipos_distintos' => $precios_por_tipo->count(),
                'precio_promedio' => round($analisis->avg('precio_promedio'), 2),
                'precio_minimo' => $analisis->min('precio_minimo'),
                'precio_maximo' => $analisis->max('precio_maximo'),
            ];

            $pdf = Pdf::loadView('pdf.maquinaria_reporte', [
                'datos' => $precios_por_tipo,
                'filtros' => $filtros,
                'estadisticas' => $estadisticas,
                'fecha_generacion' => now()->format('d/m/Y H:i'),
            ]);

            $pdf->setPaper('A4', 'landscape');

            $nombreArchivo = 'reporte_maquinaria_F1_' . $filtros['anio'] . '_' . str_pad($filtros['mes'], 2, '0', STR_PAD_LEFT) . '.pdf';

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar PDF de maquinaria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar fertilizantes a Excel (F-4)
     * GET /api/agri/reportes/fertilizantes-export
     */
    public function fertilizantesExport(Request $request)
    {
        try {
            $mes = $request->mes;
            $ano = $request->ano;

            $query = AgriAnalisisPrecio::where('tipo_formulario', 'F-4');

            // Filtrado prioritario por año y mes
            if ($mes && $ano) {
                $query->where('año', $ano)->where('mes', $mes);
            } elseif ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
            } else {
                // Por defecto: mes actual
                $query->where('año', now()->year)->where('mes', now()->month);
                $mes = now()->month;
                $ano = now()->year;
            }

            // Filtros adicionales
            if ($request->filled('region')) {
                $query->where('region', $request->region);
            }
            if ($request->filled('provincia')) {
                $query->where('provincia', $request->provincia);
            }

            $analisis = $query->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos de fertilizantes para el período seleccionado',
                    'filtros' => [
                        'mes' => $mes,
                        'ano' => $ano
                    ]
                ], 404);
            }

            // Agrupar por producto
            $precios_por_producto = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'nombre' => $first->producto_nombre,
                    'categoria' => $first->producto_categoria,
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'precio_minimo' => $items->min('precio_minimo'),
                    'precio_maximo' => $items->max('precio_maximo'),
                    'num_registros' => $items->sum('num_registros'),
                ];
            })->values();

            $first = $analisis->first();

            $filtros = [
                'region' => $request->region ?? $first->region ?? 'Nacional',
                'provincia' => $request->provincia ?? $first->provincia ?? 'Todas',
                'distrito' => $request->distrito ?? $first->distrito ?? 'Todos',
                'anio' => $ano,
                'mes' => $mes,
                'mes_nombre' => $this->getNombreMes($mes),
            ];

            $estadisticas = [
                'total_encuestas' => $analisis->count(),
                'total_registros' => $precios_por_producto->sum('num_registros'),
                'productos_distintos' => $precios_por_producto->count(),
                'precio_promedio' => round($precios_por_producto->avg('precio_promedio'), 2),
            ];

            $nombreArchivo = "Fertilizantes_F4_{$filtros['region']}_" . $this->getNombreMes($mes) . "_{$ano}.xlsx";

            // Convertir a array para la exportación
            return Excel::download(
                new FertilizantesExportArray($precios_por_producto->toArray(), $filtros, $estadisticas),
                $nombreArchivo
            );

        } catch (\Exception $e) {
            Log::error('Error en fertilizantesExport: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al exportar fertilizantes',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Exportar fertilizantes a PDF (F-4)
     * GET /api/agri/reportes/fertilizantes-pdf
     */
    public function fertilizantesPdf(Request $request)
    {
        try {
            $mes = $request->mes;
            $ano = $request->ano;

            $query = AgriAnalisisPrecio::where('tipo_formulario', 'F-4');

            // Filtrado prioritario por año y mes
            if ($mes && $ano) {
                $query->where('año', $ano)->where('mes', $mes);
            } elseif ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
            } else {
                // Por defecto: mes actual
                $query->where('año', now()->year)->where('mes', now()->month);
                $mes = now()->month;
                $ano = now()->year;
            }

            // Filtros adicionales
            if ($request->filled('region')) {
                $query->where('region', $request->region);
            }
            if ($request->filled('provincia')) {
                $query->where('provincia', $request->provincia);
            }

            $analisis = $query->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No hay datos de fertilizantes para el período seleccionado',
                    'filtros' => [
                        'mes' => $mes,
                        'ano' => $ano
                    ]
                ], 404);
            }

            // Agrupar por producto
            $precios_por_producto = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'nombre' => $first->producto_nombre,
                    'categoria' => $first->producto_categoria,
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'precio_minimo' => $items->min('precio_minimo'),
                    'precio_maximo' => $items->max('precio_maximo'),
                    'num_registros' => $items->sum('num_registros'),
                ];
            })->values();

            $first = $analisis->first();

            $filtros = [
                'region' => $request->region ?? $first->region ?? 'Nacional',
                'provincia' => $request->provincia ?? $first->provincia ?? 'Todas',
                'distrito' => $request->distrito ?? $first->distrito ?? 'Todos',
                'anio' => $ano,
                'mes' => $mes,
                'mes_nombre' => $this->getNombreMes($mes),
            ];

            $estadisticas = [
                'total_encuestas' => $analisis->count(),
                'total_registros' => $precios_por_producto->sum('num_registros'),
                'productos_distintos' => $precios_por_producto->count(),
                'precio_promedio' => round($precios_por_producto->avg('precio_promedio'), 2),
            ];

            $pdf = Pdf::loadView('pdf.fertilizantes_reporte', [
                'datos' => $precios_por_producto,
                'filtros' => $filtros,
                'estadisticas' => $estadisticas,
                'fecha_generacion' => now()->format('d/m/Y H:i'),
            ]);

            $pdf->setPaper('A4', 'landscape');

            $nombreArchivo = "Fertilizantes_F4_{$filtros['region']}_" . $this->getNombreMes($mes) . "_{$ano}.pdf";

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            Log::error('Error en fertilizantesPdf: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al generar PDF de fertilizantes',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Exportar análisis de precios de agroquímicos a Excel (F-6)
     */
    public function agroquimicosExport(Request $request)
    {
        try {
            $ano = $request->input('ano');
            $mes = $request->input('mes');
            $fecha_inicio = $request->input('fecha_inicio');
            $fecha_fin = $request->input('fecha_fin');

            // Construir consulta base
            $query = AgriAnalisisPrecio::where('tipo_formulario', 'F-6');

            // Lógica de filtrado con prioridad
            if ($ano || $mes) {
                if ($ano) {
                    $query->whereYear('fecha', $ano);
                }
                if ($mes) {
                    $query->whereMonth('fecha', $mes);
                }
            } elseif ($fecha_inicio && $fecha_fin) {
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            } else {
                $query->whereMonth('fecha', now()->month)
                      ->whereYear('fecha', now()->year);
            }

            $analisis = $query->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron datos de agroquímicos para los filtros especificados'
                ], 404);
            }

            // Obtener información de encuestas relacionadas (F-6)
            $encuestasQuery = DB::table('agri_encuestas_insumos as e')
                ->leftJoin('agri_encuestadores_insumos as enc', 'e.encuestador_id', '=', 'enc.id')
                ->leftJoin('agri_supervisores_insumos as sup', 'e.supervisor_id', '=', 'sup.id')
                ->where('e.tipo_formulario', 'F-6')
                ->where('e.estado', 'validado');

            if ($ano) {
                $encuestasQuery->where('e.anio', $ano);
            }
            if ($mes) {
                $encuestasQuery->where('e.mes', $mes);
            }

            $encuestaInfo = $encuestasQuery->select(
                'e.region',
                'e.provincia',
                'e.distrito',
                'e.anio',
                'e.mes',
                'e.fecha_validacion',
                'enc.nombres as encuestador_nombre',
                DB::raw("CONCAT(enc.apellido_paterno, ' ', enc.apellido_materno) as encuestador_apellido"),
                'enc.cargo as encuestador_cargo',
                'sup.nombres as supervisor_nombre',
                DB::raw("CONCAT(sup.apellido_paterno, ' ', sup.apellido_materno) as supervisor_apellido"),
                'sup.cargo as supervisor_cargo'
            )->first();

            // Si no hay encuesta, usar datos del análisis
            if (!$encuestaInfo) {
                $encuestaInfo = (object)[
                    'region' => $analisis->first()->region ?? 'Nacional',
                    'provincia' => $analisis->first()->provincia ?? '',
                    'distrito' => $analisis->first()->distrito ?? '',
                    'anio' => $ano ?? now()->year,
                    'mes' => $mes ?? now()->month,
                    'fecha_validacion' => null,
                    'encuestador_nombre' => '',
                    'encuestador_apellido' => '',
                    'encuestador_cargo' => '',
                    'supervisor_nombre' => '',
                    'supervisor_apellido' => '',
                    'supervisor_cargo' => ''
                ];
            }

            // Agrupar por producto y calcular estadísticas
            $precios_por_producto = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'nombre' => $first->producto_nombre,
                    'categoria' => $first->producto_categoria ?? 'OTROS',
                    'envase' => '1 lt',
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'precio_minimo' => $items->min('precio_minimo'),
                    'precio_maximo' => $items->max('precio_maximo'),
                    'desviacion_std' => round($items->isEmpty() ? 0 : sqrt($items->reduce(function($carry, $item) use ($items) {
                        $mean = $items->avg('precio_promedio');
                        return $carry + pow($item->precio_promedio - $mean, 2);
                    }, 0) / $items->count()), 2),
                    'num_registros' => $items->sum('num_registros'),
                ];
            })->values()->toArray(); // Convertir a array simple

            // Estadísticas generales
            $estadisticas = [
                'total_encuestas' => $analisis->count(),
                'total_registros' => $analisis->sum('num_registros'),
                'productos_distintos' => count($precios_por_producto),
                'precio_promedio' => round($analisis->avg('precio_promedio'), 2),
            ];

            // Información de filtros
            $filtros = [
                'region' => $encuestaInfo->region ?? 'Nacional',
                'provincia' => $encuestaInfo->provincia ?? '',
                'distrito' => $encuestaInfo->distrito ?? '',
                'anio' => $encuestaInfo->anio ?? ($ano ?? now()->year),
                'mes' => $encuestaInfo->mes ?? ($mes ?? now()->month),
                'mes_nombre' => $mes ? $this->getNombreMes($mes) : $this->getNombreMes(now()->month),
                'encuestador_nombre' => $encuestaInfo->encuestador_nombre ?? '',
                'encuestador_apellido' => $encuestaInfo->encuestador_apellido ?? '',
                'encuestador_cargo' => $encuestaInfo->encuestador_cargo ?? '',
                'supervisor_nombre' => $encuestaInfo->supervisor_nombre ?? '',
                'supervisor_apellido' => $encuestaInfo->supervisor_apellido ?? '',
                'supervisor_cargo' => $encuestaInfo->supervisor_cargo ?? '',
                'fecha_validacion' => $encuestaInfo->fecha_validacion ?? null,
            ];

            $nombreArchivo = sprintf(
                'Agroquimicos_F6_%s_%s_%s.xlsx',
                $filtros['region'],
                $filtros['mes_nombre'],
                $filtros['anio']
            );

            return Excel::download(
                new AgroquimicosExportArray($precios_por_producto, $estadisticas, $filtros),
                $nombreArchivo
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de agroquímicos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar análisis de precios de agroquímicos a PDF (F-6)
     */
    public function agroquimicosPdf(Request $request)
    {
        try {
            $ano = $request->input('ano');
            $mes = $request->input('mes');
            $fecha_inicio = $request->input('fecha_inicio');
            $fecha_fin = $request->input('fecha_fin');

            // Construir consulta base
            $query = AgriAnalisisPrecio::where('tipo_formulario', 'F-6');

            // Lógica de filtrado con prioridad
            if ($ano || $mes) {
                if ($ano) {
                    $query->where('año', $ano);
                }
                if ($mes) {
                    $query->where('mes', $mes);
                }
            } elseif ($fecha_inicio && $fecha_fin) {
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            } else {
                $query->where('mes', now()->month)
                      ->where('año', now()->year);
            }

            $analisis = $query->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron datos de agroquímicos para los filtros especificados'
                ], 404);
            }

            // Agrupar por producto y calcular estadísticas
            $precios_por_producto = $analisis->groupBy('producto_nombre')->map(function($items) {
                $first = $items->first();
                return [
                    'nombre' => $first->producto_nombre,
                    'categoria' => $first->producto_categoria ?? 'OTROS',
                    'envase' => '1 lt',
                    'precio_promedio' => round($items->avg('precio_promedio'), 2),
                    'precio_minimo' => $items->min('precio_minimo'),
                    'precio_maximo' => $items->max('precio_maximo'),
                    'desviacion_std' => round($items->isEmpty() ? 0 : sqrt($items->reduce(function($carry, $item) use ($items) {
                        $mean = $items->avg('precio_promedio');
                        return $carry + pow($item->precio_promedio - $mean, 2);
                    }, 0) / $items->count()), 2),
                    'num_registros' => $items->sum('num_registros'),
                ];
            })->values();

            // Estadísticas generales
            $estadisticas = [
                'total_encuestas' => $analisis->count(),
                'total_registros' => $analisis->sum('num_registros'),
                'productos_distintos' => $precios_por_producto->count(),
                'precio_promedio' => round($analisis->avg('precio_promedio'), 2),
            ];

            // Obtener información del encuestador y supervisor
            $encuestasQuery = DB::table('agri_encuestas_insumos as e')
                ->leftJoin('agri_encuestadores_insumos as enc', 'e.encuestador_id', '=', 'enc.id')
                ->leftJoin('agri_supervisores_insumos as sup', 'e.supervisor_id', '=', 'sup.id')
                ->where('e.tipo_formulario', 'F-6')
                ->where('e.estado', 'validado');

            if ($ano) {
                $encuestasQuery->where('e.anio', $ano);
            }
            if ($mes) {
                $encuestasQuery->where('e.mes', $mes);
            }

            $encuestaInfo = $encuestasQuery->select(
                'e.region',
                'e.provincia',
                'e.distrito',
                'e.anio',
                'e.mes',
                'e.fecha_validacion',
                'enc.nombres as encuestador_nombre',
                DB::raw("CONCAT(enc.apellido_paterno, ' ', enc.apellido_materno) as encuestador_apellido"),
                'enc.cargo as encuestador_cargo',
                'sup.nombres as supervisor_nombre',
                DB::raw("CONCAT(sup.apellido_paterno, ' ', sup.apellido_materno) as supervisor_apellido"),
                'sup.cargo as supervisor_cargo'
            )->first();

            // Si no hay encuesta, usar datos del análisis
            if (!$encuestaInfo) {
                $encuestaInfo = (object)[
                    'region' => $analisis->first()->region ?? 'Nacional',
                    'provincia' => $analisis->first()->provincia ?? '',
                    'distrito' => $analisis->first()->distrito ?? '',
                    'anio' => $ano ?? now()->year,
                    'mes' => $mes ?? now()->month,
                    'fecha_validacion' => null,
                    'encuestador_nombre' => '',
                    'encuestador_apellido' => '',
                    'encuestador_cargo' => '',
                    'supervisor_nombre' => '',
                    'supervisor_apellido' => '',
                    'supervisor_cargo' => '',
                ];
            }

            // Información de filtros
            $filtros = [
                'region' => $encuestaInfo->region ?? 'Nacional',
                'provincia' => $encuestaInfo->provincia ?? '',
                'distrito' => $encuestaInfo->distrito ?? '',
                'anio' => $ano ?? now()->year,
                'mes' => $mes ?? now()->month,
                'mes_nombre' => $mes ? $this->getNombreMes($mes) : $this->getNombreMes(now()->month),
                'encuestador_nombre' => $encuestaInfo->encuestador_nombre ?? '',
                'encuestador_apellido' => $encuestaInfo->encuestador_apellido ?? '',
                'encuestador_cargo' => $encuestaInfo->encuestador_cargo ?? '',
                'supervisor_nombre' => $encuestaInfo->supervisor_nombre ?? '',
                'supervisor_apellido' => $encuestaInfo->supervisor_apellido ?? '',
                'supervisor_cargo' => $encuestaInfo->supervisor_cargo ?? '',
                'fecha_validacion' => $encuestaInfo->fecha_validacion ?? null,
            ];

            $fecha_generacion = now()->format('d/m/Y H:i');

            // La vista espera 'datos' no 'precios_por_producto'
            $datos = $precios_por_producto;

            $pdf = PDF::loadView('pdf.agroquimicos_reporte', compact(
                'datos',
                'estadisticas',
                'filtros',
                'fecha_generacion'
            ))->setPaper('a4', 'landscape');

            $nombreArchivo = sprintf(
                'Agroquimicos_F6_%s_%s_%s.pdf',
                $filtros['region'],
                $filtros['mes_nombre'],
                $filtros['anio']
            );

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar PDF de agroquímicos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar análisis de precios de transporte a Excel (F-14)
     */
    public function transporteExportNuevo(Request $request)
    {
        try {
            $ano = $request->input('ano');
            $mes = $request->input('mes');
            $fecha_inicio = $request->input('fecha_inicio');
            $fecha_fin = $request->input('fecha_fin');

            // Construir consulta base
            $query = AgriAnalisisPrecio::where('tipo_formulario', 'F-14');

            // Lógica de filtrado con prioridad
            if ($ano || $mes) {
                if ($ano) {
                    $query->where('año', $ano);
                }
                if ($mes) {
                    $query->where('mes', $mes);
                }
            } elseif ($fecha_inicio && $fecha_fin) {
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            } else {
                $query->where('mes', now()->month)
                      ->where('año', now()->year);
            }

            $analisis = $query->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron datos de transporte para los filtros especificados'
                ], 404);
            }

            // Agrupar por ruta (origen-destino-via) y producto
            $rutas = $analisis->map(function($item) {
                return [
                    'tipo' => $item->producto_tipo ?? 'TRANSPORTE',
                    'origen' => $item->origen ?? 'N/A',
                    'destino' => $item->destino ?? 'N/A',
                    'via' => $item->via_transporte ?? 'Terrestre',
                    'producto' => $item->producto_nombre,
                    'unidad_medida' => $item->producto_unidad ?? 'TM',
                    'precio_minimo' => $item->precio_minimo,
                    'precio_maximo' => $item->precio_maximo,
                    'precio_promedio' => $item->precio_promedio,
                    'num_registros' => $item->num_registros,
                    'es_dentro_region' => $item->es_dentro_region ?? true,
                ];
            });

            // Separar por dentro y fuera de región
            $dentro_region = $rutas->where('es_dentro_region', true)->values();
            $fuera_region = $rutas->where('es_dentro_region', false)->values();

            // Estadísticas generales
            $estadisticas = [
                'total_encuestas' => $analisis->count(),
                'total_registros' => $analisis->sum('num_registros'),
                'rutas_distintas' => $rutas->count(),
                'precio_promedio' => round($analisis->avg('precio_promedio'), 2),
                'total_dentro_region' => $dentro_region->count(),
                'total_fuera_region' => $fuera_region->count(),
            ];

            // Obtener información del encuestador y supervisor
            $encuestasQuery = DB::table('agri_encuestas_insumos as e')
                ->leftJoin('agri_encuestadores_insumos as enc', 'e.encuestador_id', '=', 'enc.id')
                ->leftJoin('agri_supervisores_insumos as sup', 'e.supervisor_id', '=', 'sup.id')
                ->where('e.tipo_formulario', 'F-14')
                ->where('e.estado', 'validado');

            if ($ano) {
                $encuestasQuery->where('e.anio', $ano);
            }
            if ($mes) {
                $encuestasQuery->where('e.mes', $mes);
            }

            $encuestaInfo = $encuestasQuery->select(
                'e.region',
                'e.provincia',
                'e.distrito',
                'e.anio',
                'e.mes',
                'e.fecha_validacion',
                'enc.nombres as encuestador_nombre',
                DB::raw("CONCAT(enc.apellido_paterno, ' ', enc.apellido_materno) as encuestador_apellido"),
                'enc.cargo as encuestador_cargo',
                'sup.nombres as supervisor_nombre',
                DB::raw("CONCAT(sup.apellido_paterno, ' ', sup.apellido_materno) as supervisor_apellido"),
                'sup.cargo as supervisor_cargo'
            )->first();

            // Si no hay encuesta, usar datos del análisis
            if (!$encuestaInfo) {
                $encuestaInfo = (object)[
                    'region' => $analisis->first()->region ?? 'Nacional',
                    'provincia' => $analisis->first()->provincia ?? 'Todas',
                    'distrito' => $analisis->first()->distrito ?? 'Todos',
                    'anio' => $ano ?? now()->year,
                    'mes' => $mes ?? now()->month,
                    'fecha_validacion' => null,
                    'encuestador_nombre' => '',
                    'encuestador_apellido' => '',
                    'encuestador_cargo' => '',
                    'supervisor_nombre' => '',
                    'supervisor_apellido' => '',
                    'supervisor_cargo' => '',
                ];
            }

            // Información de filtros
            $filtros = [
                'region' => $encuestaInfo->region ?? 'Nacional',
                'provincia' => $encuestaInfo->provincia ?? 'Todas',
                'distrito' => $encuestaInfo->distrito ?? 'Todos',
                'anio' => $encuestaInfo->anio ?? ($ano ?? now()->year),
                'mes' => $encuestaInfo->mes ?? ($mes ?? now()->month),
                'mes_nombre' => $mes ? $this->getNombreMes($mes) : $this->getNombreMes(now()->month),
                'encuestador_nombre' => $encuestaInfo->encuestador_nombre ?? '',
                'encuestador_apellido' => $encuestaInfo->encuestador_apellido ?? '',
                'encuestador_cargo' => $encuestaInfo->encuestador_cargo ?? '',
                'supervisor_nombre' => $encuestaInfo->supervisor_nombre ?? '',
                'supervisor_apellido' => $encuestaInfo->supervisor_apellido ?? '',
                'supervisor_cargo' => $encuestaInfo->supervisor_cargo ?? '',
                'fecha_validacion' => $encuestaInfo->fecha_validacion ?? null,
            ];

            $nombreArchivo = sprintf(
                'Transporte_F14_%s_%s_%s.xlsx',
                $filtros['region'],
                $filtros['mes_nombre'],
                $filtros['anio']
            );

            return Excel::download(
                new TransporteExport($dentro_region, $fuera_region, $estadisticas, $filtros),
                $nombreArchivo
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte de transporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar análisis de precios de transporte a PDF (F-14)
     */
    public function transportePdfNuevo(Request $request)
    {
        try {
            $ano = $request->input('ano');
            $mes = $request->input('mes');
            $fecha_inicio = $request->input('fecha_inicio');
            $fecha_fin = $request->input('fecha_fin');

            // Construir consulta base
            $query = AgriAnalisisPrecio::where('tipo_formulario', 'F-14');

            // Lógica de filtrado con prioridad
            if ($ano || $mes) {
                if ($ano) {
                    $query->where('año', $ano);
                }
                if ($mes) {
                    $query->where('mes', $mes);
                }
            } elseif ($fecha_inicio && $fecha_fin) {
                $query->whereBetween('fecha', [$fecha_inicio, $fecha_fin]);
            } else {
                $query->where('mes', now()->month)
                      ->where('año', now()->year);
            }

            $analisis = $query->get();

            if ($analisis->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron datos de transporte para los filtros especificados'
                ], 404);
            }

            // Agrupar por ruta (origen-destino-via) y producto
            $rutas = $analisis->map(function($item) {
                return [
                    'tipo' => $item->producto_tipo ?? 'TRANSPORTE',
                    'origen' => $item->origen ?? 'N/A',
                    'destino' => $item->destino ?? 'N/A',
                    'via' => $item->via_transporte ?? 'Terrestre',
                    'producto' => $item->producto_nombre,
                    'unidad_medida' => $item->producto_unidad ?? 'TM',
                    'precio_minimo' => $item->precio_minimo,
                    'precio_maximo' => $item->precio_maximo,
                    'precio_promedio' => $item->precio_promedio,
                    'num_registros' => $item->num_registros,
                    'es_dentro_region' => $item->es_dentro_region ?? true,
                ];
            });

            // Separar por dentro y fuera de región
            $dentro_region = $rutas->where('es_dentro_region', true)->values();
            $fuera_region = $rutas->where('es_dentro_region', false)->values();

            // Estadísticas generales
            $estadisticas = [
                'total_encuestas' => $analisis->count(),
                'total_registros' => $analisis->sum('num_registros'),
                'rutas_distintas' => $rutas->count(),
                'precio_promedio' => round($analisis->avg('precio_promedio'), 2),
                'total_dentro_region' => $dentro_region->count(),
                'total_fuera_region' => $fuera_region->count(),
            ];

            // Obtener información del encuestador y supervisor
            $encuestasQuery = DB::table('agri_encuestas_insumos as e')
                ->leftJoin('agri_encuestadores_insumos as enc', 'e.encuestador_id', '=', 'enc.id')
                ->leftJoin('agri_supervisores_insumos as sup', 'e.supervisor_id', '=', 'sup.id')
                ->where('e.tipo_formulario', 'F-14')
                ->where('e.estado', 'validado');

            if ($ano) {
                $encuestasQuery->where('e.anio', $ano);
            }
            if ($mes) {
                $encuestasQuery->where('e.mes', $mes);
            }

            $encuestaInfo = $encuestasQuery->select(
                'e.region',
                'e.provincia',
                'e.distrito',
                'e.anio',
                'e.mes',
                'e.fecha_validacion',
                'enc.nombres as encuestador_nombre',
                DB::raw("CONCAT(enc.apellido_paterno, ' ', enc.apellido_materno) as encuestador_apellido"),
                'enc.cargo as encuestador_cargo',
                'sup.nombres as supervisor_nombre',
                DB::raw("CONCAT(sup.apellido_paterno, ' ', sup.apellido_materno) as supervisor_apellido"),
                'sup.cargo as supervisor_cargo'
            )->first();

            // Si no hay encuesta, usar datos del análisis
            if (!$encuestaInfo) {
                $encuestaInfo = (object)[
                    'region' => $analisis->first()->region ?? 'Nacional',
                    'provincia' => $analisis->first()->provincia ?? 'Todas',
                    'distrito' => $analisis->first()->distrito ?? 'Todos',
                    'anio' => $ano ?? now()->year,
                    'mes' => $mes ?? now()->month,
                    'fecha_validacion' => null,
                    'encuestador_nombre' => '',
                    'encuestador_apellido' => '',
                    'encuestador_cargo' => '',
                    'supervisor_nombre' => '',
                    'supervisor_apellido' => '',
                    'supervisor_cargo' => '',
                ];
            }

            // Información de filtros
            $filtros = [
                'region' => $encuestaInfo->region ?? 'Nacional',
                'provincia' => $encuestaInfo->provincia ?? 'Todas',
                'distrito' => $encuestaInfo->distrito ?? 'Todos',
                'anio' => $encuestaInfo->anio ?? ($ano ?? now()->year),
                'mes' => $encuestaInfo->mes ?? ($mes ?? now()->month),
                'mes_nombre' => $mes ? $this->getNombreMes($mes) : $this->getNombreMes(now()->month),
                'encuestador_nombre' => $encuestaInfo->encuestador_nombre ?? '',
                'encuestador_apellido' => $encuestaInfo->encuestador_apellido ?? '',
                'encuestador_cargo' => $encuestaInfo->encuestador_cargo ?? '',
                'supervisor_nombre' => $encuestaInfo->supervisor_nombre ?? '',
                'supervisor_apellido' => $encuestaInfo->supervisor_apellido ?? '',
                'supervisor_cargo' => $encuestaInfo->supervisor_cargo ?? '',
                'fecha_validacion' => $encuestaInfo->fecha_validacion ?? null,
            ];

            $fecha_generacion = now()->format('d/m/Y H:i');

            $pdf = PDF::loadView('pdf.transporte_reporte', compact(
                'dentro_region',
                'fuera_region',
                'estadisticas',
                'filtros',
                'fecha_generacion'
            ))->setPaper('a4', 'landscape');

            $nombreArchivo = sprintf(
                'Transporte_F14_%s_%s_%s.pdf',
                $filtros['region'],
                $filtros['mes_nombre'],
                $filtros['anio']
            );

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar PDF de transporte',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener nombre del mes en español
     */
    private function getNombreMes($mes)
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        return $meses[$mes] ?? 'Desconocido';
    }
}



