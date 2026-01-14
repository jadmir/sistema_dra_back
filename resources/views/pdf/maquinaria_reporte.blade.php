<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Maquinaria F-1 - SIEA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            color: #333;
            line-height: 1.3;
        }

        .container {
            width: 100%;
            max-width: 100%;
        }

        /* ENCABEZADO PRINCIPAL */
        .header {
            background: linear-gradient(135deg, #FFC107 0%, #FFD54F 100%);
            border: 3px solid #8B4513;
            padding: 15px;
            margin-bottom: 10px;
            position: relative;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            width: 15%;
            vertical-align: middle;
        }

        .header-center {
            display: table-cell;
            width: 70%;
            text-align: center;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            width: 15%;
            text-align: center;
            vertical-align: middle;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #8B4513;
        }

        .header-title {
            color: #8B4513;
            font-weight: bold;
        }

        .header-title h1 {
            font-size: 11px;
            margin-bottom: 3px;
        }

        .header-title h2 {
            font-size: 9px;
            margin-bottom: 3px;
        }

        .header-title h3 {
            font-size: 12px;
            font-weight: bold;
        }

        .code-box {
            background: #FFC107;
            border: 3px solid #8B4513;
            padding: 10px 8px;
            font-size: 32px;
            font-weight: bold;
            color: #8B4513;
            border-radius: 5px;
        }

        /* INFORMACIÓN DEL REPORTE */
        .info-section {
            background: #FFFDE7;
            border: 2px solid #FFC107;
            padding: 8px;
            margin-bottom: 10px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .info-label {
            display: table-cell;
            width: 25%;
            font-weight: bold;
            color: #8B4513;
            padding: 3px;
        }

        .info-value {
            display: table-cell;
            width: 75%;
            padding: 3px;
        }

        /* RESUMEN ESTADÍSTICO */
        .stats-section {
            background: #FFC107;
            border: 2px solid #8B4513;
            padding: 8px;
            margin-bottom: 10px;
        }

        .stats-title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 8px;
        }

        .stats-grid {
            display: table;
            width: 100%;
        }

        .stat-item {
            display: table-cell;
            text-align: center;
            background: white;
            border: 1px solid #FFC107;
            padding: 8px 5px;
            vertical-align: middle;
        }

        .stat-label {
            font-size: 8px;
            color: #666;
            display: block;
            margin-bottom: 3px;
        }

        .stat-value {
            font-size: 14px;
            font-weight: bold;
            display: block;
        }

        .stat-blue { color: #2196F3; }
        .stat-green { color: #4CAF50; }
        .stat-orange { color: #FF9800; }
        .stat-purple { color: #9C27B0; }

        /* TABLA DE DATOS */
        .data-section {
            margin-bottom: 10px;
        }

        .section-title {
            background: #FFC107;
            border: 2px solid #8B4513;
            padding: 6px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th {
            background: #FFE082;
            border: 1px solid #333;
            padding: 6px 4px;
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            color: #333;
        }

        td {
            border: 1px solid #333;
            padding: 5px 4px;
            text-align: center;
            font-size: 8px;
        }

        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        .bg-categoria { background: #FFF3E0; }
        .bg-promedio { background: #E8F5E9; }
        .bg-minimo { background: #E3F2FD; }
        .bg-maximo { background: #FCE4EC; }
        .bg-total { background: #FFF9C4; }

        .col-num { width: 5%; }
        .col-nombre { width: 30%; }
        .col-categoria { width: 15%; }
        .col-precio { width: 12%; }
        .col-desv { width: 10%; }
        .col-reg { width: 8%; }

        .total-row {
            background: #FFE082;
            font-weight: bold;
        }

        .total-row td {
            border: 2px solid #8B4513;
        }

        /* FOOTER */
        .footer {
            margin-top: 15px;
            padding: 8px;
            background: #FFFDE7;
            border-top: 2px solid #FFC107;
        }

        .footer-notes {
            font-size: 7px;
            color: #666;
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .footer-signature {
            text-align: right;
            font-size: 8px;
            font-weight: bold;
            color: #8B4513;
        }

        /* UTILIDADES */
        .bold { font-weight: bold; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <div class="container">
        <!-- ENCABEZADO PRINCIPAL -->
        <div class="header">
            <div class="header-content">
                <div class="header-left">
                    <div class="logo">¡SIEA</div>
                </div>
                <div class="header-center">
                    <div class="header-title">
                        <h1>Dirección General de Estadística, Seguimiento y Evaluación de Políticas - DGESEP</h1>
                        <h2>Dirección de Estadística e Información Agraria - DEIA</h2>
                        <h3>REPORTE DE PRECIOS DE ALQUILER DE MAQUINARIA AGRÍCOLA (S/.)</h3>
                    </div>
                </div>
                <div class="header-right">
                    <div class="code-box">F-1</div>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL REPORTE -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">REGIÓN:</div>
                <div class="info-value">{{ $filtros['region'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">PROVINCIA:</div>
                <div class="info-value">{{ $filtros['provincia'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">PERÍODO:</div>
                <div class="info-value">{{ $filtros['mes_nombre'] }} {{ $filtros['anio'] }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">FECHA GENERACIÓN:</div>
                <div class="info-value">{{ $fecha_generacion }}</div>
            </div>
        </div>

        <!-- RESUMEN ESTADÍSTICO -->
        <div class="stats-section">
            <div class="stats-title">RESUMEN ESTADÍSTICO</div>
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
                    <span class="stat-label">Tipos Distintos</span>
                    <span class="stat-value stat-orange">{{ $estadisticas['tipos_distintos'] }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Precio Promedio</span>
                    <span class="stat-value stat-purple">S/. {{ number_format($estadisticas['precio_promedio'], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- TABLA DE DATOS -->
        <div class="data-section">
            <div class="section-title">DETALLE DE PRECIOS POR TIPO DE MAQUINARIA</div>

            <table>
                <thead>
                    <tr>
                        <th class="col-num">#</th>
                        <th class="col-nombre">NOMBRE MAQUINARIA</th>
                        <th class="col-categoria">CATEGORÍA</th>
                        <th class="col-precio">PRECIO<br>PROMEDIO</th>
                        <th class="col-precio">PRECIO<br>MÍNIMO</th>
                        <th class="col-precio">PRECIO<br>MÁXIMO</th>
                        <th class="col-desv">DESV.<br>ESTÁNDAR</th>
                        <th class="col-reg">N° REG.</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 1; @endphp
                    @foreach($datos as $item)
                    <tr>
                        <td class="text-center">{{ $contador++ }}</td>
                        <td class="text-left">{{ $item['nombre'] }}</td>
                        <td class="text-center bg-categoria">{{ $item['categoria'] }}</td>
                        <td class="text-right bg-promedio bold">S/. {{ number_format($item['precio_promedio'], 2) }}</td>
                        <td class="text-right bg-minimo">S/. {{ number_format($item['precio_minimo'], 2) }}</td>
                        <td class="text-right bg-maximo">S/. {{ number_format($item['precio_maximo'], 2) }}</td>
                        <td class="text-right">{{ number_format($item['desviacion_estandar'], 2) }}</td>
                        <td class="text-center bold">{{ $item['num_registros'] }}</td>
                    </tr>
                    @endforeach

                    <!-- FILA DE TOTALES -->
                    <tr class="total-row">
                        <td colspan="3" class="text-right">TOTALES:</td>
                        <td class="text-right bg-promedio">S/. {{ number_format($estadisticas['precio_promedio'], 2) }}</td>
                        <td class="text-right bg-minimo">S/. {{ number_format($estadisticas['precio_minimo'], 2) }}</td>
                        <td class="text-right bg-maximo">S/. {{ number_format($estadisticas['precio_maximo'], 2) }}</td>
                        <td></td>
                        <td class="text-center bg-total">{{ $estadisticas['total_registros'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-notes">
                * Precios expresados en Soles (S/.) por hora de alquiler<br>
                ** Los precios promedio son calculados a partir de múltiples encuestas realizadas en el período indicado<br>
                *** Desviación estándar: Medida de dispersión de los precios (menor valor indica mayor estabilidad)
            </div>
            <div class="footer-signature">
                SISTEMA DE INFORMACIÓN ESTADÍSTICA AGRARIA - SIEA | F1-EISA-DGESEP-DEIA
            </div>
        </div>
    </div>
</body>
</html>
