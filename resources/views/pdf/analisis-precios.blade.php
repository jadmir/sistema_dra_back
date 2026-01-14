<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Análisis de Precios - {{ $filtros['tipo_formulario'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #333;
        }
        .header {
            background-color: #2E7D32;
            color: white;
            padding: 15px;
            text-align: center;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10px;
            margin: 2px 0;
        }
        .info-box {
            background-color: #E8F5E9;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #2E7D32;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px;
            font-size: 9px;
        }
        .info-box td strong {
            color: #1B5E20;
        }
        .estadisticas {
            margin-bottom: 15px;
            background-color: #F1F8F4;
            padding: 10px;
            border-left: 4px solid #388E3C;
        }
        .estadisticas h3 {
            font-size: 12px;
            color: #2E7D32;
            margin-bottom: 8px;
        }
        .estadisticas table {
            width: 100%;
        }
        .estadisticas td {
            padding: 4px 8px;
            font-size: 9px;
        }
        .estadisticas td:first-child {
            font-weight: bold;
            width: 40%;
        }
        table.datos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.datos th {
            background-color: #388E3C;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #2E7D32;
        }
        table.datos td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            font-size: 8px;
        }
        table.datos tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table.datos tr:hover {
            background-color: #E8F5E9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        .badge-aumento {
            background-color: #FFEBEE;
            color: #C62828;
        }
        .badge-disminucion {
            background-color: #E8F5E9;
            color: #2E7D32;
        }
        .badge-estable {
            background-color: #E3F2FD;
            color: #1565C0;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #666;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE ANÁLISIS DE PRECIOS - {{ $filtros['tipo_formulario_nombre'] }}</h1>
        <p>Sistema Integrado de Estadística Agraria - SIEA</p>
        <p>Formulario {{ $filtros['tipo_formulario'] }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td width="50%"><strong>Periodo:</strong> {{ $filtros['periodo'] }}</td>
                <td width="50%"><strong>Fecha de generación:</strong> {{ $fecha_generacion }}</td>
            </tr>
            <tr>
                <td><strong>Región:</strong> {{ $filtros['region'] }}</td>
                <td><strong>Provincia:</strong> {{ $filtros['provincia'] }}</td>
            </tr>
        </table>
    </div>

    <div class="estadisticas">
        <h3>📊 ESTADÍSTICAS GENERALES</h3>
        <table>
            <tr>
                <td>Total de registros:</td>
                <td><strong>{{ $estadisticas['total_registros'] }}</strong></td>
                <td>Precio promedio general:</td>
                <td><strong>S/. {{ number_format($estadisticas['precio_promedio_general'], 2) }}</strong></td>
            </tr>
            <tr>
                <td>Precio mínimo:</td>
                <td><strong>S/. {{ number_format($estadisticas['precio_minimo_general'], 2) }}</strong></td>
                <td>Precio máximo:</td>
                <td><strong>S/. {{ number_format($estadisticas['precio_maximo_general'], 2) }}</strong></td>
            </tr>
            <tr>
                <td>Productos distintos:</td>
                <td><strong>{{ $estadisticas['productos_distintos'] }}</strong></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    <h3 style="font-size: 12px; color: #2E7D32; margin: 15px 0 10px 0;">DETALLE DE ANÁLISIS DE PRECIOS</h3>

    <table class="datos">
        <thead>
            <tr>
                <th width="7%">Fecha</th>
                <th width="15%">Producto</th>
                <th width="10%">Categoría</th>
                <th width="8%">P. Promedio</th>
                <th width="7%">P. Mín.</th>
                <th width="7%">P. Máx.</th>
                <th width="7%">Mediana</th>
                <th width="6%">Desv.</th>
                <th width="6%">C.V.</th>
                <th width="7%">Var. %</th>
                <th width="8%">Tendencia</th>
                <th width="5%">Reg.</th>
                <th width="7%">Ubicación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                <td>{{ $item->producto_nombre }}</td>
                <td>{{ $item->producto_categoria }}</td>
                <td class="text-right">S/. {{ number_format($item->precio_promedio, 2) }}</td>
                <td class="text-right">{{ number_format($item->precio_minimo, 2) }}</td>
                <td class="text-right">{{ number_format($item->precio_maximo, 2) }}</td>
                <td class="text-right">{{ number_format($item->precio_mediana, 2) }}</td>
                <td class="text-right">{{ number_format($item->desviacion_estandar, 2) }}</td>
                <td class="text-right">{{ number_format($item->coeficiente_variacion, 2) }}%</td>
                <td class="text-right">{{ number_format($item->variacion_porcentual, 2) }}%</td>
                <td class="text-center">
                    <span class="badge badge-{{ $item->tendencia }}">
                        {{ strtoupper($item->tendencia) }}
                    </span>
                </td>
                <td class="text-center">{{ $item->num_registros }}</td>
                <td>{{ substr($item->provincia, 0, 15) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="13" class="text-center">No se encontraron registros</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>SIEA - Sistema Integrado de Estadística Agraria | Página {PAGE_NUM} de {PAGE_COUNT}</p>
        <p>Documento generado el {{ $fecha_generacion }}</p>
    </div>
</body>
</html>
