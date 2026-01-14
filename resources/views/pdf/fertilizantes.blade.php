<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Fertilizantes - F-4</title>
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
            background-color: #FF6F00;
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
            background-color: #FFF3E0;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #FF6F00;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px;
            font-size: 9px;
        }
        .info-box td strong {
            color: #E65100;
        }
        .estadisticas {
            margin-bottom: 15px;
            background-color: #FFF8E1;
            padding: 10px;
            border-left: 4px solid #F57C00;
        }
        .estadisticas h3 {
            font-size: 12px;
            color: #FF6F00;
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
            background-color: #F57C00;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #FF6F00;
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
            background-color: #FFF3E0;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>🌱 REPORTE DE FERTILIZANTES - FORMULARIO F-4</h1>
        <p>Sistema Integrado de Estadística Agraria - SIEA</p>
        <p>Análisis de Precios de Fertilizantes y Abonos</p>
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
                <td>Fertilizantes distintos:</td>
                <td><strong>{{ $estadisticas['tipos_distintos'] }}</strong></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    <h3 style="font-size: 12px; color: #FF6F00; margin: 15px 0 10px 0;">DETALLE DE ANÁLISIS DE PRECIOS</h3>

    <table class="datos">
        <thead>
            <tr>
                <th width="7%">Fecha</th>
                <th width="18%">Fertilizante</th>
                <th width="12%">Tipo</th>
                <th width="9%">P. Promedio</th>
                <th width="8%">P. Mín.</th>
                <th width="8%">P. Máx.</th>
                <th width="7%">Mediana</th>
                <th width="6%">Desv.</th>
                <th width="6%">C.V.</th>
                <th width="7%">Var. %</th>
                <th width="8%">Tendencia</th>
                <th width="4%">Reg.</th>
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
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center">No se encontraron registros</td>
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
