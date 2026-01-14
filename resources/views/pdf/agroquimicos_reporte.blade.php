<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Agroquímicos F-6 - SIEA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 7px; color: #333; line-height: 1.2; }
        .container { width: 100%; }

        /* ENCABEZADO */
        .header { background: linear-gradient(135deg, #FFC107 0%, #FFD54F 100%); border: 3px solid #8B4513; padding: 12px; margin-bottom: 8px; }
        .header-content { display: table; width: 100%; }
        .header-left { display: table-cell; width: 12%; vertical-align: middle; }
        .header-center { display: table-cell; width: 76%; text-align: center; vertical-align: middle; }
        .header-right { display: table-cell; width: 12%; text-align: center; vertical-align: middle; }
        .logo { font-size: 20px; font-weight: bold; color: #8B4513; }
        .logo-sub { font-size: 7px; color: #8B4513; }
        .header-title h1 { font-size: 10px; margin-bottom: 2px; color: #8B4513; }
        .header-title h2 { font-size: 8px; margin-bottom: 2px; color: #8B4513; }
        .header-title h3 { font-size: 11px; font-weight: bold; color: #000; }
        .code-box { background: #FFC107; border: 3px solid #8B4513; padding: 8px 6px; font-size: 28px; font-weight: bold; color: #8B4513; border-radius: 4px; }

        /* INFORMACIÓN */
        .info-section { background: #FFFDE7; border: 2px solid #FFC107; padding: 6px; margin-bottom: 8px; }
        .info-row { display: table; width: 100%; margin-bottom: 3px; }
        .info-label { display: table-cell; width: 20%; font-weight: bold; color: #8B4513; padding: 2px; font-size: 7px; }
        .info-value { display: table-cell; width: 80%; padding: 2px; font-size: 7px; }

        /* RESUMEN ESTADÍSTICO */
        .stats-section { background: #FFC107; border: 2px solid #8B4513; padding: 6px; margin-bottom: 8px; }
        .stats-title { text-align: center; font-size: 10px; font-weight: bold; color: #8B4513; margin-bottom: 5px; }
        .stats-grid { display: table; width: 100%; }
        .stat-item { display: table-cell; text-align: center; background: white; border: 1px solid #FFC107; padding: 6px 4px; vertical-align: middle; }
        .stat-label { font-size: 7px; color: #666; display: block; margin-bottom: 2px; }
        .stat-value { font-size: 12px; font-weight: bold; display: block; }
        .stat-blue { color: #2196F3; }
        .stat-green { color: #4CAF50; }
        .stat-orange { color: #FF9800; }
        .stat-purple { color: #9C27B0; }

        /* TABLA */
        .section-title { background: #FFC107; border: 2px solid #8B4513; padding: 5px; text-align: center; font-size: 9px; font-weight: bold; color: #8B4513; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 6px; }
        th { background: #FFE082; border: 1px solid #333; padding: 3px 2px; text-align: center; font-weight: bold; }
        td { border: 1px solid #333; padding: 2px 1px; text-align: center; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .bg-categoria { background: #FFF3E0; font-weight: bold; border: 2px solid #8B4513; }
        .bg-promedio { background: #E8F5E9; }
        .bg-total { background: #FFF9C4; }

        /* FOOTER */
        .footer { margin-top: 10px; padding: 6px; background: #FFFDE7; border-top: 2px solid #FFC107; }
        .footer-signature { text-align: right; font-size: 7px; font-weight: bold; color: #8B4513; }
    </style>
</head>
<body>
    <div class="container">
        <!-- ENCABEZADO -->
        <div class="header">
            <div class="header-content">
                <div class="header-left">
                    <div class="logo">¡SIEA</div>
                    <div class="logo-sub">Sistema Integrado de<br>Estadística Agraria</div>
                </div>
                <div class="header-center">
                    <div class="header-title">
                        <h1>Dirección General de Estadística, Seguimiento y Evaluación de Políticas - DGESEP</h1>
                        <h2>Dirección de Estadística e Información Agraria - DEIA</h2>
                        <h3>PRECIOS DE PRINCIPALES AGROQUÍMICOS (S/.)</h3>
                    </div>
                </div>
                <div class="header-right">
                    <div class="code-box">F-6</div>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">I. UBICACIÓN POLÍTICA</div>
                <div class="info-value"></div>
                <div class="info-label">II. AÑO Y MES</div>
                <div class="info-value"></div>
            </div>
            <div class="info-row">
                <div class="info-label">Región:</div>
                <div class="info-value">{{ $filtros['region'] }}</div>
                <div class="info-label">Año:</div>
                <div class="info-value">{{ $filtros['anio'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Provincia:</div>
                <div class="info-value">{{ $filtros['provincia'] }}</div>
                <div class="info-label">Mes:</div>
                <div class="info-value">{{ $filtros['mes'] }} - {{ $filtros['mes_nombre'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Distrito:</div>
                <div class="info-value">{{ $filtros['distrito'] }}</div>
                <div class="info-label">Fecha generación:</div>
                <div class="info-value">{{ $fecha_generacion }}</div>
            </div>
        </div>

        <!-- RESUMEN ESTADÍSTICO -->
        <div class="stats-section">
            <div class="stats-title">RESUMEN ESTADÍSTICO DEL PERÍODO</div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Total Encuestas</span>
                    <span class="stat-value stat-blue">{{ $estadisticas['total_encuestas'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Registros</span>
                    <span class="stat-value stat-green">{{ $estadisticas['total_registros'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Productos Distintos</span>
                    <span class="stat-value stat-orange">{{ $estadisticas['productos_distintos'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Precio Promedio</span>
                    <span class="stat-value stat-purple">S/. {{ number_format($estadisticas['precio_promedio'], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- TABLA DE DATOS -->
        <div class="section-title">III. INFORMACIÓN DE PRECIOS DE PRINCIPALES AGROQUÍMICOS</div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 12%;">TIPO</th>
                    <th rowspan="2" style="width: 18%;">AGROQUÍMICO</th>
                    <th rowspan="2" style="width: 10%;">ENVASE</th>
                    <th colspan="3" style="width: 40%;">PRECIOS (S/.)</th>
                    <th rowspan="2" style="width: 12%;">PRECIO<br>PROMEDIO</th>
                    <th rowspan="2" style="width: 8%;">N° REG.</th>
                </tr>
                <tr>
                    <th>MÍNIMO</th>
                    <th>PROMEDIO</th>
                    <th>MÁXIMO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Mapear categorías de la BD a nombres estándar
                    $categoriasMap = [
                        'acaricida' => 'ACARICIDAS',
                        'adherente' => 'ADHERENTES',
                        'fungicida' => 'FUNGICIDAS',
                        'Fungicidas' => 'FUNGICIDAS',
                        'Herbicidas' => 'HERBICIDAS',
                        'Insecticidas' => 'INSECTICIDAS',
                        'nutriente foliar' => 'NUTRIENTES FOLIARES',
                        'regulador de crecimiento' => 'REGULADORES DE CRECIMIENTO',
                        'OTROS' => 'OTROS'
                    ];

                    // Agrupar por categoría normalizada
                    $agrupadoPorCategoria = collect($datos)->groupBy(function($item) use ($categoriasMap) {
                        $cat = $item['categoria'] ?? 'OTROS';
                        return $categoriasMap[$cat] ?? 'OTROS';
                    });

                    // Orden de categorías para mostrar
                    $ordenCategorias = [
                        'ACARICIDAS',
                        'ADHERENTES',
                        'FUNGICIDAS',
                        'HERBICIDAS',
                        'INSECTICIDAS',
                        'NUTRIENTES FOLIARES',
                        'REGULADORES DE CRECIMIENTO',
                        'OTROS'
                    ];
                @endphp

                @foreach($ordenCategorias as $nombreCategoria)
                    @php
                        $productos = $agrupadoPorCategoria->get($nombreCategoria, collect());
                    @endphp

                    @if($productos->isNotEmpty())
                        <!-- Fila de categoría -->
                        <tr>
                            <td colspan="8" class="bg-categoria text-left">
                                {{ $nombreCategoria }}
                            </td>
                        </tr>

                        <!-- Productos -->
                        @foreach($productos as $producto)
                        <tr>
                            <td class="text-left" style="font-size: 6px;">{{ $nombreCategoria }}</td>
                            <td class="text-left"><strong>{{ $producto['nombre'] }}</strong></td>
                            <td style="font-size: 6px;">{{ $producto['envase'] }}</td>
                            <td class="text-right">S/. {{ number_format($producto['precio_minimo'], 2) }}</td>
                            <td class="text-right">S/. {{ number_format($producto['precio_promedio'], 2) }}</td>
                            <td class="text-right">S/. {{ number_format($producto['precio_maximo'], 2) }}</td>
                            <td class="text-right bg-promedio"><strong>S/. {{ number_format($producto['precio_promedio'], 2) }}</strong></td>
                            <td><strong>{{ $producto['num_registros'] }}</strong></td>
                        </tr>
                        @endforeach
                    @endif
                @endforeach

                <!-- TOTALES -->
                <tr>
                    <td colspan="6" class="text-right" style="background: #FFE082; font-weight: bold;">
                        PRECIO PROMEDIO GENERAL:
                    </td>
                    <td class="text-right bg-promedio" style="font-weight: bold; font-size: 8px;">
                        S/. {{ number_format($estadisticas['precio_promedio'], 2) }}
                    </td>
                    <td class="bg-total" style="font-weight: bold; font-size: 8px;">
                        {{ $estadisticas['total_registros'] }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- IV. OBSERVACIONES -->
        <div style="background: #FFC107; border: 2px solid #8B4513; padding: 5px; text-align: center; font-size: 9px; font-weight: bold; color: #8B4513; margin-top: 8px; margin-bottom: 4px;">
            IV. OBSERVACIONES
        </div>
        <div style="background: #FFFDE7; border: 1px solid #FFC107; padding: 10px; margin-bottom: 8px;">
            @php
                // Calcular estadísticas para observaciones
                $totalProductos = $estadisticas['productos_distintos'] ?? 0;
                $precioPromedio = $estadisticas['precio_promedio'] ?? 0;

                // Encontrar min/max entre todos los productos
                $precioMinimo = PHP_INT_MAX;
                $precioMaximo = 0;
                foreach ($productos_por_categoria as $categoria => $productos) {
                    foreach ($productos as $producto) {
                        $min = floatval($producto['precio_minimo'] ?? 0);
                        $max = floatval($producto['precio_maximo'] ?? 0);
                        if ($min > 0 && $min < $precioMinimo) $precioMinimo = $min;
                        if ($max > $precioMaximo) $precioMaximo = $max;
                    }
                }
                if ($precioMinimo == PHP_INT_MAX) $precioMinimo = 0;
            @endphp
            <div style="font-size: 8px; line-height: 1.6;">
                <div style="margin-bottom: 4px;">- Total de productos registrados: {{ $totalProductos }}</div>
                <div style="margin-bottom: 4px;">- Precio promedio general: S/. {{ number_format($precioPromedio, 2) }}</div>
                <div>- Rango de precios: S/. {{ number_format($precioMinimo, 2) }} - S/. {{ number_format($precioMaximo, 2) }}</div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL ENCUESTADOR -->
        <div style="background: #FFF3E0; border: 1px solid #FFC107; padding: 6px; margin-bottom: 8px;">
            <div style="font-weight: bold; font-size: 9px; color: #8B4513; margin-bottom: 4px;">ENCUESTADOR</div>
            <div style="margin-bottom: 3px; font-size: 7px;">
                <span style="font-weight: bold;">Nombre y Apellido:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; width: 70%; padding: 2px;">
                    {{ !empty($filtros['encuestador_nombre']) ? $filtros['encuestador_nombre'] . ' ' . ($filtros['encuestador_apellido'] ?? '') : '' }}
                </span>
            </div>
            <div style="font-size: 7px;">
                <span style="font-weight: bold;">Cargo:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; width: 75%; padding: 2px;">
                    {{ $filtros['encuestador_cargo'] ?? '' }}
                </span>
            </div>
        </div>

        <!-- INFORMACIÓN DEL SUPERVISOR -->
        <div style="background: #FFF3E0; border: 1px solid #FFC107; padding: 6px; margin-bottom: 8px;">
            <div style="font-weight: bold; font-size: 9px; color: #8B4513; margin-bottom: 4px;">SUPERVISOR</div>
            <div style="margin-bottom: 3px; font-size: 7px;">
                <span style="font-weight: bold;">Nombre y Apellido:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; width: 70%; padding: 2px;">
                    {{ !empty($filtros['supervisor_nombre']) ? $filtros['supervisor_nombre'] . ' ' . ($filtros['supervisor_apellido'] ?? '') : '' }}
                </span>
            </div>
            <div style="margin-bottom: 3px; font-size: 7px;">
                <span style="font-weight: bold;">Cargo:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; width: 75%; padding: 2px;">
                    {{ $filtros['supervisor_cargo'] ?? '' }}
                </span>
            </div>
            <div style="font-size: 7px;">
                <span style="font-weight: bold;">Fecha de Supervisión:</span>
                <span style="border-bottom: 1px solid #000; display: inline-block; width: 60%; padding: 2px;">
                    {{ !empty($filtros['fecha_validacion']) ? date('d/m/Y', strtotime($filtros['fecha_validacion'])) : '' }}
                </span>
            </div>
        </div>

        <!-- FIRMA -->
        <div style="text-align: center; margin-top: 15px; margin-bottom: 8px;">
            <div style="font-size: 7px; font-weight: bold; margin-bottom: 25px;">Firma:</div>
            <div style="border-top: 1px solid #000; width: 40%; margin: 0 auto;"></div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div style="font-size: 6px; color: #666; margin-bottom: 4px;">
                * Productos no registrados en SENASA<br>
                ** Los precios promedio son calculados a partir de múltiples encuestas realizadas en el período indicado
            </div>
            <div class="footer-signature">
                SISTEMA DE INFORMACIÓN ESTADÍSTICA AGRARIA - SIEA | F3-EISA-DGESEP-DEIA
            </div>
        </div>
    </div>
</body>
</html>
