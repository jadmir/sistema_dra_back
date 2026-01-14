<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Transporte F-14 - SIEA</title>
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
        .stat-item { display: table-cell; text-align: center; background: white; border: 1px solid #FFC107; padding: 5px 3px; vertical-align: middle; }
        .stat-label { font-size: 6px; color: #666; display: block; margin-bottom: 2px; }
        .stat-value { font-size: 11px; font-weight: bold; display: block; }
        .stat-blue { color: #2196F3; }
        .stat-green { color: #4CAF50; }
        .stat-orange { color: #FF9800; }
        .stat-purple { color: #9C27B0; }
        .stat-cyan { color: #0288D1; }
        .stat-amber { color: #F57C00; }

        /* TABLA */
        .section-title { background: #FFC107; border: 2px solid #8B4513; padding: 5px; text-align: center; font-size: 9px; font-weight: bold; color: #8B4513; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; font-size: 6px; }
        th { background: #FFE082; border: 1px solid #333; padding: 2px 1px; text-align: center; font-weight: bold; }
        td { border: 1px solid #333; padding: 2px 1px; text-align: center; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .bg-seccion { background: #FFF3E0; font-weight: bold; border: 2px solid #8B4513; }
        .bg-precio { background: #E8F5E9; }

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
                        <h1>Dirección General de Estadística, Seguimiento y Evaluación de Políticas-DGESEP</h1>
                        <h2>Dirección de Estadística e Información Agraria - DEIA</h2>
                        <h3>PRECIOS (S/.) DE TRANSPORTE DE PRODUCTOS AGROPECUARIOS Y AGROINDUSTRIALES ALIMENTICIOS</h3>
                    </div>
                </div>
                <div class="header-right">
                    <div class="code-box">F-14</div>
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
                    <span class="stat-label">Rutas Distintas</span>
                    <span class="stat-value stat-orange">{{ $estadisticas['rutas_distintas'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Precio Promedio</span>
                    <span class="stat-value stat-purple">S/. {{ number_format($estadisticas['precio_promedio'], 2) }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Dentro Región</span>
                    <span class="stat-value stat-cyan">{{ $estadisticas['total_dentro_region'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Fuera Región</span>
                    <span class="stat-value stat-amber">{{ $estadisticas['total_fuera_region'] }}</span>
                </div>
            </div>
        </div>

        <!-- TABLA DE DATOS -->
        <div class="section-title">III. INFORMACIÓN DE PRECIOS DE TRANSPORTE DE PRODUCTOS</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">TIPO</th>
                    <th style="width: 14%;">ORIGEN</th>
                    <th style="width: 14%;">DESTINO</th>
                    <th style="width: 8%;">VÍA</th>
                    <th style="width: 20%;">PRODUCTO</th>
                    <th style="width: 8%;">UNIDAD</th>
                    <th style="width: 12%;">PRECIO<br>MÍNIMO</th>
                    <th style="width: 12%;">PRECIO<br>MÁXIMO</th>
                    <th style="width: 6%;">N° REG.</th>
                </tr>
            </thead>
            <tbody>
                <!-- DENTRO DE LA REGIÓN -->
                <tr>
                    <td colspan="9" class="bg-seccion text-left" style="padding: 3px;">
                        Dentro de la región:
                    </td>
                </tr>
                @if($dentro_region->isNotEmpty())
                    @foreach($dentro_region as $ruta)
                    <tr>
                        <td style="font-size: 6px;">{{ $ruta['tipo'] }}</td>
                        <td class="text-left">{{ $ruta['origen'] }}</td>
                        <td class="text-left">{{ $ruta['destino'] }}</td>
                        <td style="font-size: 6px;">{{ $ruta['via'] }}</td>
                        <td class="text-left"><strong>{{ $ruta['producto'] }}</strong></td>
                        <td style="font-size: 6px;">{{ $ruta['unidad_medida'] }}</td>
                        <td class="text-right bg-precio">S/. {{ number_format($ruta['precio_minimo'], 2) }}</td>
                        <td class="text-right bg-precio">S/. {{ number_format($ruta['precio_maximo'], 2) }}</td>
                        <td><strong>{{ $ruta['num_registros'] }}</strong></td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" style="padding: 10px; color: #999; text-align: center;">
                            No hay rutas dentro de la región registradas para este período
                        </td>
                    </tr>
                @endif

                <!-- FUERA DE LA REGIÓN -->
                <tr>
                    <td colspan="9" class="bg-seccion text-left" style="padding: 3px;">
                        Fuera de la región:
                    </td>
                </tr>
                @if($fuera_region->isNotEmpty())
                    @foreach($fuera_region as $ruta)
                    <tr>
                        <td style="font-size: 6px;">{{ $ruta['tipo'] }}</td>
                        <td class="text-left">{{ $ruta['origen'] }}</td>
                        <td class="text-left">{{ $ruta['destino'] }}</td>
                        <td style="font-size: 6px;">{{ $ruta['via'] }}</td>
                        <td class="text-left"><strong>{{ $ruta['producto'] }}</strong></td>
                        <td style="font-size: 6px;">{{ $ruta['unidad_medida'] }}</td>
                        <td class="text-right bg-precio">S/. {{ number_format($ruta['precio_minimo'], 2) }}</td>
                        <td class="text-right bg-precio">S/. {{ number_format($ruta['precio_maximo'], 2) }}</td>
                        <td><strong>{{ $ruta['num_registros'] }}</strong></td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" style="padding: 10px; color: #999; text-align: center;">
                            No hay rutas fuera de la región registradas para este período
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

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
                * Precios expresados en Soles (S/.) con IGV incluido<br>
                ** Los precios pueden variar según la temporada y disponibilidad de transporte
            </div>
            <div class="footer-signature">
                SISTEMA DE INFORMACIÓN ESTADÍSTICA AGRARIA - SIEA | F7-EISA-DGESEP-DEIA
            </div>
        </div>
    </div>
</body>
</html>
