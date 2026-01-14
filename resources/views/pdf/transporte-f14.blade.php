<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario F-14 - Precios de Transporte</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            color: #000;
            padding: 10px;
        }
        .header-section {
            width: 100%;
            margin-bottom: 10px;
        }
        .header-top {
            background-color: #FFC107;
            padding: 10px;
            text-align: center;
            border: 2px solid #000;
        }
        .logo-siea {
            background-color: #FFC107;
            color: #000;
            font-weight: bold;
            font-size: 16px;
            padding: 5px;
            display: inline-block;
            border: 2px solid #000;
        }
        .header-top h1 {
            font-size: 9px;
            font-weight: bold;
            margin: 2px 0;
            line-height: 1.2;
        }
        .form-code {
            background-color: #FFC107;
            color: #000;
            font-weight: bold;
            font-size: 20px;
            padding: 8px;
            text-align: center;
            border: 2px solid #000;
            float: right;
            margin-top: -40px;
        }
        .ubicacion-section {
            border: 1px solid #000;
            padding: 8px;
            margin: 10px 0;
            background-color: #FFF;
        }
        .ubicacion-section h3 {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .ubicacion-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .ubicacion-item {
            display: table-cell;
            padding: 3px 5px;
        }
        .ubicacion-label {
            font-weight: bold;
            font-size: 8px;
        }
        .ubicacion-value {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 150px;
            font-size: 8px;
        }
        .info-reference {
            border: 1px solid #000;
            padding: 5px;
            margin: 10px 0;
            background-color: #FFFDE7;
        }
        .info-reference h3 {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .info-row {
            margin: 3px 0;
        }
        .info-label {
            font-weight: bold;
            font-size: 8px;
            display: inline-block;
            width: 80px;
        }
        .info-value {
            border-bottom: 1px dotted #000;
            display: inline-block;
            min-width: 100px;
            font-size: 8px;
        }
        .transporte-section {
            margin: 10px 0;
        }
        .transporte-section h3 {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
            background-color: #FFF;
            padding: 5px;
        }
        table.transporte {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table.transporte th {
            background-color: #FFF;
            border: 1px solid #000;
            padding: 5px 2px;
            text-align: center;
            font-size: 7px;
            font-weight: bold;
            vertical-align: middle;
        }
        table.transporte td {
            border: 1px solid #000;
            padding: 4px 2px;
            font-size: 7px;
            text-align: center;
            vertical-align: middle;
        }
        .tipo-transporte-header {
            background-color: #FFF;
            font-weight: bold;
            text-align: left;
            padding-left: 5px;
        }
        .ruta-nombre {
            text-align: left;
            padding-left: 5px;
        }
        .precio-promedio {
            background-color: #FFFDE7;
            font-weight: bold;
        }
        .observaciones {
            border: 1px solid #000;
            padding: 8px;
            margin: 10px 0;
            min-height: 40px;
        }
        .observaciones h4 {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .firma-section {
            margin-top: 15px;
            border: 1px solid #000;
            padding: 10px;
        }
        .firma-section h4 {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .firma-table {
            width: 100%;
        }
        .firma-table td {
            padding: 5px;
            vertical-align: top;
        }
        .firma-box {
            border: 1px solid #000;
            padding: 8px;
            min-height: 60px;
        }
        .firma-label {
            font-weight: bold;
            font-size: 8px;
            margin-bottom: 3px;
        }
        .firma-field {
            border-bottom: 1px dotted #000;
            display: block;
            margin: 3px 0;
            min-height: 15px;
            font-size: 8px;
        }
        .footer-code {
            text-align: right;
            font-size: 7px;
            margin-top: 10px;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- ENCABEZADO -->
    <div class="header-section">
        <div class="header-top">
            <div class="logo-siea">¡SIEA</div>
            <h1>Dirección General de Estadística, Seguimiento y Evaluación de Políticas-DGESEP</h1>
            <h1>Dirección de Estadística e Información Agraria - DEIA</h1>
            <h1>PRECIOS (S/) DE TRANSPORTE DE PRODUCTOS AGROPECUARIOS Y AGROINDUSTRIALES ALIMENTICIOS</h1>
        </div>
        <div class="form-code">F-<br>1<br>4</div>
    </div>

    <!-- I. UBICACIÓN POLÍTICA -->
    <div class="ubicacion-section">
        <h3>I. Ubicación política</h3>
        <div class="ubicacion-row">
            <div class="ubicacion-item">
                <span class="ubicacion-label">1. Región:</span>
                <span class="ubicacion-value">{{ $filtros['region'] ?? 'TODAS' }}</span>
            </div>
        </div>
        <div class="ubicacion-row">
            <div class="ubicacion-item">
                <span class="ubicacion-label">2. Provincia:</span>
                <span class="ubicacion-value">{{ $filtros['provincia'] ?? 'TODAS' }}</span>
            </div>
        </div>
        <div class="ubicacion-row">
            <div class="ubicacion-item">
                <span class="ubicacion-label">3. Distrito:</span>
                <span class="ubicacion-value">{{ $filtros['distrito'] ?? 'TODOS' }}</span>
            </div>
        </div>
    </div>

    <!-- II. AÑO Y MES DE REFERENCIA -->
    <div class="info-reference">
        <h3>II. Año y mes de referencia</h3>
        <div class="info-row">
            <span class="info-label">Año:</span>
            <span class="info-value">{{ $filtros['año'] ?? date('Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mes:</span>
            <span class="info-value">{{ $filtros['mes_nombre'] ?? strtoupper(\Carbon\Carbon::now()->locale('es')->monthName) }}</span>
        </div>
    </div>

    <!-- III. INFORMACIÓN DE PRECIOS DE TRANSPORTE -->
    <div class="transporte-section">
        <h3>III. Información de precios de transporte de productos</h3>

        <table class="transporte">
            <thead>
                <tr>
                    <th rowspan="2" width="8%">Tipo</th>
                    <th rowspan="2" width="12%">Origen</th>
                    <th rowspan="2" width="12%">Destino</th>
                    <th rowspan="2" width="10%">Vía:<br>a. Terrestre<br>b. Fluvial<br>c. Aéreo</th>
                    <th rowspan="2" width="15%">Producto<br>(Agrícola, Pecuario,<br>Agroindustrial)</th>
                    <th rowspan="2" width="8%">Unidad de<br>medida</th>
                    <th colspan="2" width="20%">Precio con IGV<br>(S/. x UM) -</th>
                </tr>
                <tr>
                    <th width="10%">Mínimo</th>
                    <th width="10%">Máximo</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Agrupar por tipo de transporte
                    $tiposTransporte = [
                        'Dentro de la región:' => [],
                        'Fuera de la región:' => []
                    ];

                    foreach($datos as $item) {
                        // Determinar si es dentro o fuera de la región
                        $esInterno = (isset($item->es_interno) && $item->es_interno) ||
                                    (strpos(strtolower($item->producto_nombre ?? ''), 'región') !== false);
                        $grupo = $esInterno ? 'Dentro de la región:' : 'Fuera de la región:';
                        $tiposTransporte[$grupo][] = $item;
                    }
                @endphp

                @foreach($tiposTransporte as $tipo => $rutas)
                    @if(count($rutas) > 0)
                    <tr>
                        <td colspan="8" class="tipo-transporte-header">{{ $tipo }}</td>
                    </tr>

                    @foreach($rutas as $ruta)
                    @php
                        // Extraer origen y destino del nombre del producto
                        $nombreRuta = $ruta->producto_nombre;
                        $partes = explode(' - ', $nombreRuta);
                        $origen = $partes[0] ?? $nombreRuta;
                        $destino = $partes[1] ?? '';
                        $tipoVehiculo = $ruta->producto_categoria ?? 'Camión';
                        $via = 'a. Terrestre';
                        $producto = $ruta->producto_transportado ?? 'Productos agrícolas';
                        $unidad = $ruta->unidad_medida ?? 'Tonelada';
                    @endphp
                    <tr>
                        <td>{{ $tipoVehiculo }}</td>
                        <td class="ruta-nombre">{{ $origen }}</td>
                        <td class="ruta-nombre">{{ $destino }}</td>
                        <td>{{ $via }}</td>
                        <td class="ruta-nombre">{{ $producto }}</td>
                        <td>{{ $unidad }}</td>
                        <td class="text-right precio-promedio">{{ number_format($ruta->precio_minimo, 2) }}</td>
                        <td class="text-right precio-promedio">{{ number_format($ruta->precio_maximo, 2) }}</td>
                    </tr>
                    @endforeach

                    @if(count($rutas) < 4)
                        @for($i = count($rutas); $i < 4; $i++)
                        <tr>
                            <td></td>
                            <td class="ruta-nombre"></td>
                            <td class="ruta-nombre"></td>
                            <td></td>
                            <td class="ruta-nombre"></td>
                            <td></td>
                            <td class="precio-promedio"></td>
                            <td class="precio-promedio"></td>
                        </tr>
                        @endfor
                    @endif
                    @endif
                @endforeach

                @if(count($datos) == 0)
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">
                        No se encontraron datos para el periodo seleccionado
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- IV. OBSERVACIONES -->
    <div class="observaciones">
        <h4>IV. Observaciones</h4>
        <div style="font-size: 8px;">
            @if(isset($estadisticas))
            - Total de rutas registradas: {{ $estadisticas['total_registros'] ?? count($datos) }}<br>
            - Costo promedio general: S/. {{ number_format($estadisticas['precio_promedio_general'] ?? 0, 2) }}<br>
            - Rango de costos: S/. {{ number_format($estadisticas['precio_minimo_general'] ?? 0, 2) }} - S/. {{ number_format($estadisticas['precio_maximo_general'] ?? 0, 2) }}
            @endif
        </div>
    </div>

    <!-- V. DEL ENCUESTADOR Y SUPERVISOR -->
    <div class="firma-section">
        <h4>V. Del encuestador y supervisor</h4>
        <table class="firma-table">
            <tr>
                <td width="48%">
                    <div class="firma-box">
                        <div class="firma-label">ENCUESTADOR</div>
                        <span class="firma-field">Nombres: ...............................................</span>
                        <span class="firma-field">Apellidos: ...............................................</span>
                        <span class="firma-field">Cargo: ....................................................</span>
                    </div>
                </td>
                <td width="4%"></td>
                <td width="48%">
                    <div class="firma-box">
                        <div class="firma-label">SUPERVISOR</div>
                        <span class="firma-field">Nombres: ...............................................</span>
                        <span class="firma-field">Apellidos: ...............................................</span>
                        <span class="firma-field">Cargo: ....................................................</span>
                        <span class="firma-field">Fecha de Supervisión: ___/___/_______</span>
                        <span class="firma-field">Firma: ......................................................</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-code">
        F7-EISA-DGESEP-DEIA
    </div>

    <!-- INFORMACIÓN DE GENERACIÓN -->
    <div style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 7px; color: #666;">
        <strong>Documento generado por SIEA</strong><br>
        Fecha: {{ $fecha_generacion }}<br>
        Periodo: {{ $filtros['periodo'] ?? 'N/A' }}
    </div>
</body>
</html>
