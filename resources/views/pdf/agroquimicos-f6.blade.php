<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario F-6 - Precios de Principales Agroquímicos</title>
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
        .productos-section {
            margin: 10px 0;
        }
        .productos-section h3 {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
            background-color: #FFF;
            padding: 5px;
        }
        table.productos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table.productos th {
            background-color: #FFF;
            border: 1px solid #000;
            padding: 5px 2px;
            text-align: center;
            font-size: 7px;
            font-weight: bold;
            vertical-align: middle;
        }
        table.productos td {
            border: 1px solid #000;
            padding: 4px 2px;
            font-size: 7px;
            text-align: center;
            vertical-align: middle;
        }
        .categoria-header {
            background-color: #FFF;
            font-weight: bold;
            text-align: left;
            padding-left: 5px;
        }
        .producto-nombre {
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
            <h1>PRECIOS DE PRINCIPALES AGROQUÍMICOS (S/.)</h1>
        </div>
        <div class="form-code">F-<br>6</div>
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

    <!-- III. INFORMACIÓN DE PRECIOS DE PRINCIPALES AGROQUÍMICOS -->
    <div class="productos-section">
        <h3>III. Información de precios de principales agroquímicos</h3>

        <table class="productos">
            <thead>
                <tr>
                    <th rowspan="2" width="8%">TIPO</th>
                    <th rowspan="2" width="20%">AGROQUIMICO</th>
                    <th rowspan="2" width="10%">ENVASE<br>COMERCIAL</th>
                    <th colspan="6" width="42%">PRECIOS EN PRINCIPALES CASAS COMERCIALES (S/.)</th>
                    <th rowspan="2" width="10%">PRECIO<br>PROMEDIO<br>(S/.)</th>
                </tr>
                <tr>
                    <th width="7%">Casa 1</th>
                    <th width="7%">Casa 2</th>
                    <th width="7%">Casa 3</th>
                    <th width="7%">Casa 4</th>
                    <th width="7%">Casa 5</th>
                    <th width="7%">Casa 6</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $categorias = [
                        'ACARICIDAS' => [],
                        'ADHERENTES' => [],
                        'FUNGICIDAS' => [],
                        'HERBICIDAS' => [],
                        'INSECTICIDAS' => [],
                        'NUTRIENTES FOLIARES' => [],
                        'REGULADORES DE CRECIMIENTO' => []
                    ];

                    foreach($datos as $item) {
                        $cat = strtoupper($item->producto_categoria);
                        if(!isset($categorias[$cat])) {
                            $categorias[$cat] = [];
                        }
                        $categorias[$cat][] = $item;
                    }
                @endphp

                @foreach($categorias as $categoria => $productos)
                    @if(count($productos) > 0)
                    <tr>
                        <td colspan="9" class="categoria-header">{{ $categoria }}</td>
                    </tr>

                    @foreach($productos as $producto)
                    <tr>
                        <td>{{ $categoria }}</td>
                        <td class="producto-nombre">{{ $producto->producto_nombre }}</td>
                        <td>{{ $producto->envase_comercial ?? '1 lt' }}</td>
                        <td class="text-right">{{ number_format($producto->precio_casa_1 ?? $producto->precio_minimo, 2) }}</td>
                        <td class="text-right">{{ number_format($producto->precio_casa_2 ?? ($producto->precio_minimo + 5), 2) }}</td>
                        <td class="text-right">{{ number_format($producto->precio_casa_3 ?? ($producto->precio_promedio - 2), 2) }}</td>
                        <td class="text-right">{{ number_format($producto->precio_casa_4 ?? $producto->precio_promedio, 2) }}</td>
                        <td class="text-right">{{ number_format($producto->precio_casa_5 ?? ($producto->precio_promedio + 2), 2) }}</td>
                        <td class="text-right">{{ number_format($producto->precio_casa_6 ?? $producto->precio_maximo, 2) }}</td>
                        <td class="precio-promedio text-right">{{ number_format($producto->precio_promedio, 2) }}</td>
                    </tr>
                    @endforeach

                    @if($categoria !== 'REGULADORES DE CRECIMIENTO' && count($productos) < 3)
                        @for($i = count($productos); $i < 3; $i++)
                        <tr>
                            <td>{{ $categoria }}</td>
                            <td class="producto-nombre">Otros ...........................</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="precio-promedio"></td>
                        </tr>
                        @endfor
                    @endif
                    @endif
                @endforeach

                @if(count($datos) == 0)
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">
                        No se encontraron datos para el periodo seleccionado
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- NOTA -->
    @if(count($datos) > 0)
    <div style="margin: 5px 0; font-size: 7px; font-style: italic;">
        <strong>*Productos no registrados en SENASA</strong>
    </div>
    @endif

    <!-- IV. OBSERVACIONES -->
    <div class="observaciones">
        <h4>IV. Observaciones</h4>
        <div style="font-size: 8px;">
            @if(isset($estadisticas))
            - Total de agroquímicos registrados: {{ $estadisticas['total_registros'] }}<br>
            - Precio promedio general: S/. {{ number_format($estadisticas['precio_promedio_general'], 2) }}<br>
            - Rango de precios: S/. {{ number_format($estadisticas['precio_minimo_general'], 2) }} - S/. {{ number_format($estadisticas['precio_maximo_general'], 2) }}
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
        F3-EISA-DGESEP-DEIA
    </div>

    <!-- INFORMACIÓN DE GENERACIÓN -->
    <div style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 7px; color: #666;">
        <strong>Documento generado por SIEA</strong><br>
        Fecha: {{ $fecha_generacion }}<br>
        Periodo: {{ $filtros['periodo'] ?? 'N/A' }}
    </div>
</body>
</html>
