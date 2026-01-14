<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario F-14 - Transporte</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        /* HEADER PRINCIPAL */
        .header-row {
            background-color: #FFC107;
            border: 3px solid #000;
        }

        .header-logo {
            width: 15%;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            border-right: 2px solid #000;
        }

        .header-title {
            width: 70%;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            border-right: 2px solid #000;
        }

        .header-badge {
            width: 15%;
            padding: 8px;
            text-align: center;
            background-color: #FF6B35;
            color: white;
            font-weight: bold;
            font-size: 28px;
            vertical-align: middle;
        }

        /* SECCIONES */
        .section-title {
            background-color: #fff;
            padding: 6px;
            font-weight: bold;
            font-size: 10px;
            border: 2px solid #000;
            border-top: none;
        }

        .section-content {
            padding: 5px;
            border: 2px solid #000;
            border-top: 1px solid #ccc;
        }

        .section-dual {
            display: table;
            width: 100%;
        }

        .section-left {
            width: 60%;
            float: left;
            border: 2px solid #000;
            border-right: 1px solid #000;
        }

        .section-right {
            width: 40%;
            float: right;
            border: 2px solid #000;
            border-left: 1px solid #000;
        }

        .info-row {
            padding: 4px 8px;
            border-bottom: 1px solid #ccc;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 30%;
        }

        /* TABLA DE DATOS */
        .data-table {
            margin-top: 10px;
            border: 2px solid #000;
        }

        .data-table th {
            background-color: #E0E0E0;
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
        }

        .data-table td {
            border: 1px solid #ccc;
            padding: 4px;
            font-size: 8px;
        }

        .data-table .category-header {
            background-color: #f5f5f5;
            font-weight: bold;
            padding: 5px;
            border: 1px solid #000;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        /* OBSERVACIONES */
        .observaciones-section {
            margin-top: 10px;
            border: 3px solid #000;
            padding: 8px;
        }

        .observaciones-title {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .observaciones-content {
            font-size: 8px;
            line-height: 1.5;
        }

        /* ENCUESTADOR Y SUPERVISOR */
        .personal-section {
            margin-top: 10px;
            border: 3px solid #000;
        }

        .personal-title {
            background-color: #f5f5f5;
            padding: 6px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            border-bottom: 2px solid #000;
        }

        .personal-container {
            display: table;
            width: 100%;
        }

        .personal-left {
            width: 50%;
            float: left;
            padding: 8px;
            border-right: 2px solid #000;
        }

        .personal-right {
            width: 50%;
            float: right;
            padding: 8px;
        }

        .personal-header {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 6px;
            text-align: center;
            text-decoration: underline;
        }

        .personal-field {
            margin-bottom: 4px;
            font-size: 8px;
        }

        .personal-label {
            font-weight: bold;
            display: inline-block;
            width: 35%;
        }

        /* FOOTER */
        .footer {
            margin-top: 8px;
            text-align: right;
            font-size: 8px;
            font-weight: bold;
            color: #666;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* PAGE BREAK */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- HEADER PRINCIPAL -->
    <table>
        <tr class="header-row">
            <td class="header-logo">SIEA</td>
            <td class="header-title">
                Dirección General de Estadística, Seguimiento y Evaluación de Políticas - DGESEP<br>
                Dirección de Estadística e Información Agraria - DEIA<br>
                <strong>PRECIOS (S/) DE TRANSPORTE DE PRODUCTOS AGROPECUARIOS Y AGROINDUSTRIALES ALIMENTICIOS</strong>
            </td>
            <td class="header-badge">F-14</td>
        </tr>
    </table>

    <!-- SECCIONES I Y II -->
    <div class="clearfix" style="margin-top: 10px;">
        <div class="section-left">
            <div class="section-title">I. UBICACIÓN POLÍTICA</div>
            <div class="section-content">
                <div class="info-row">
                    <span class="info-label">Región:</span>
                    <span>{{ $filtros['region'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Provincia:</span>
                    <span>{{ $filtros['provincia'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Distrito:</span>
                    <span>{{ $filtros['distrito'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        <div class="section-right">
            <div class="section-title">II. AÑO Y MES DE REFERENCIA</div>
            <div class="section-content">
                <div class="info-row">
                    <span class="info-label">Año:</span>
                    <span>{{ $filtros['año'] ?? date('Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mes:</span>
                    <span>{{ $filtros['mes_nombre'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha:</span>
                    <span>{{ \Carbon\Carbon::parse($fecha_generacion)->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN III: TABLA DE DATOS -->
    <div style="margin-top: 10px;">
        <div class="section-title" style="text-align: center;">
            III. INFORMACIÓN DE PRECIOS DE TRANSPORTE DE PRODUCTOS
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 12%;">Tipo</th>
                <th rowspan="2" style="width: 15%;">Origen</th>
                <th rowspan="2" style="width: 15%;">Destino</th>
                <th rowspan="2" style="width: 10%;">Vía:<br>a. Terrestre<br>b. Fluvial<br>c. Aéreo</th>
                <th rowspan="2" style="width: 20%;">Producto<br>(Agrícola, Pecuario, Agroindustrial)</th>
                <th rowspan="2" style="width: 10%;">Unidad de medida</th>
                <th colspan="2" style="width: 18%;">Precio con IGV (S/. x UM)</th>
            </tr>
            <tr>
                <th style="width: 9%;">Mínimo</th>
                <th style="width: 9%;">Máximo</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Agrupar por tipo de transporte
                $dentroRegion = [];
                $fueraRegion = [];

                foreach($datos as $item) {
                    $nombreRuta = strtolower($item->producto_nombre ?? '');
                    $tipo = strtolower($item->tipo_vehiculo ?? '');
                    $categoria = strtolower($item->producto_categoria ?? '');

                    // Clasificar: los que tienen "Camión" van a "Fuera de la región"
                    // Los que tienen "Transporte" o "Flete" van a "Dentro de la región"
                    $esCamion = (strpos($categoria, 'camión') !== false ||
                                strpos($tipo, 'camión') !== false);

                    if ($esCamion) {
                        $fueraRegion[] = $item;
                    } else {
                        $dentroRegion[] = $item;
                    }
                }
            @endphp

            <!-- DENTRO DE LA REGIÓN -->
            @if(count($dentroRegion) > 0)
            <tr>
                <td colspan="8" class="category-header">Dentro de la región:</td>
            </tr>
            @foreach($dentroRegion as $ruta)
            @php
                $nombreRuta = $ruta->producto_nombre;
                $partes = explode(' - ', $nombreRuta);
                $origen = $partes[0] ?? $nombreRuta;
                $destino = $partes[1] ?? '';
                $tipoVehiculo = $ruta->producto_categoria ?? $ruta->tipo_vehiculo ?? 'Camión';
                $via = 'a. Terrestre';
                $producto = $ruta->producto_transportado ?? 'Productos agrícolas';
                $unidad = $ruta->unidad_medida ?? 'Tonelada';
            @endphp
            <tr>
                <td class="text-left">{{ $tipoVehiculo }}</td>
                <td class="text-left">{{ $origen }}</td>
                <td class="text-left">{{ $destino }}</td>
                <td class="text-center">{{ $via }}</td>
                <td class="text-left">{{ $producto }}</td>
                <td class="text-center">{{ $unidad }}</td>
                <td class="text-right">{{ number_format($ruta->precio_minimo, 2) }}</td>
                <td class="text-right">{{ number_format($ruta->precio_maximo, 2) }}</td>
            </tr>
            @endforeach
            @endif

            <!-- FUERA DE LA REGIÓN -->
            @if(count($fueraRegion) > 0)
            <tr>
                <td colspan="8" class="category-header">Fuera de la región:</td>
            </tr>
            @foreach($fueraRegion as $ruta)
            @php
                $nombreRuta = $ruta->producto_nombre;
                $partes = explode(' - ', $nombreRuta);
                $origen = $partes[0] ?? $nombreRuta;
                $destino = $partes[1] ?? '';
                $tipoVehiculo = $ruta->producto_categoria ?? $ruta->tipo_vehiculo ?? 'Camión';
                $via = 'a. Terrestre';
                $producto = $ruta->producto_transportado ?? 'Productos agrícolas';
                $unidad = $ruta->unidad_medida ?? 'Tonelada';
            @endphp
            <tr>
                <td class="text-left">{{ $tipoVehiculo }}</td>
                <td class="text-left">{{ $origen }}</td>
                <td class="text-left">{{ $destino }}</td>
                <td class="text-center">{{ $via }}</td>
                <td class="text-left">{{ $producto }}</td>
                <td class="text-center">{{ $unidad }}</td>
                <td class="text-right">{{ number_format($ruta->precio_minimo, 2) }}</td>
                <td class="text-right">{{ number_format($ruta->precio_maximo, 2) }}</td>
            </tr>
            @endforeach
            @endif

            @if(count($datos) == 0)
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">
                    No se encontraron registros para los filtros seleccionados
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- SECCIÓN IV: OBSERVACIONES -->
    <div class="observaciones-section">
        <div class="observaciones-title">IV. OBSERVACIONES</div>
        <div class="observaciones-content">
            @if(isset($estadisticas))
            - Total de rutas registradas: {{ $estadisticas['total_registros'] ?? count($datos) }}<br>
            - Costo promedio general: S/. {{ number_format($estadisticas['precio_promedio_general'] ?? 0, 2) }}<br>
            - Rango de costos: S/. {{ number_format($estadisticas['precio_minimo_general'] ?? 0, 2) }} - S/. {{ number_format($estadisticas['precio_maximo_general'] ?? 0, 2) }}<br>
            - Rutas distintas: {{ $estadisticas['rutas_distintas'] ?? 0 }}
            @endif
        </div>
    </div>

    <!-- SECCIÓN V: ENCUESTADOR Y SUPERVISOR -->
    <div class="personal-section">
        <div class="personal-title">V. ENCUESTADOR Y SUPERVISOR</div>
        <div class="personal-container clearfix">
            <div class="personal-left">
                <div class="personal-header">ENCUESTADOR</div>
                <div class="personal-field">
                    <span class="personal-label">Nombres:</span>
                    <span>{{ $filtros['encuestador_nombre'] ?? 'N/A' }}</span>
                </div>
                <div class="personal-field">
                    <span class="personal-label">Apellidos:</span>
                    <span>{{ $filtros['encuestador_apellido'] ?? 'N/A' }}</span>
                </div>
                <div class="personal-field">
                    <span class="personal-label">Cargo:</span>
                    <span>{{ $filtros['encuestador_cargo'] ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="personal-right">
                <div class="personal-header">SUPERVISOR</div>
                <div class="personal-field">
                    <span class="personal-label">Nombres:</span>
                    <span>{{ $filtros['supervisor_nombre'] ?? 'N/A' }}</span>
                </div>
                <div class="personal-field">
                    <span class="personal-label">Apellidos:</span>
                    <span>{{ $filtros['supervisor_apellido'] ?? 'N/A' }}</span>
                </div>
                <div class="personal-field">
                    <span class="personal-label">Cargo:</span>
                    <span>{{ $filtros['supervisor_cargo'] ?? 'N/A' }}</span>
                </div>
                <div class="personal-field">
                    <span class="personal-label">Fecha supervisión:</span>
                    <span>
                        @if(isset($filtros['fecha_validacion']) && $filtros['fecha_validacion'])
                            {{ \Carbon\Carbon::parse($filtros['fecha_validacion'])->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="personal-field" style="margin-top: 10px;">
                    <span class="personal-label">Firma:</span>
                    <span>_____________________</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        F7-EISA-DGESEP-DEIA
    </div>
</body>
</html>
