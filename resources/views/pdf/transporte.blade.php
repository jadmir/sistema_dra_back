<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Transporte - F-14</title>
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
            background-color: #1565C0;
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
            background-color: #E3F2FD;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #1565C0;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px;
            font-size: 9px;
        }
        .info-box td strong {
            color: #0D47A1;
        }
        .estadisticas {
            margin-bottom: 15px;
            background-color: #F1F8FB;
            padding: 10px;
            border-left: 4px solid #1976D2;
        }
        .estadisticas h3 {
            font-size: 12px;
            color: #1565C0;
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
            background-color: #1976D2;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #1565C0;
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
            background-color: #E3F2FD;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .icon {
            font-size: 10px;
            margin-right: 2px;
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
        .highlight {
            background-color: #FFF9C4;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚚 REPORTE DE TRANSPORTE - FORMULARIO F-14</h1>
        <p>Sistema Integrado de Estadística Agraria - SIEA</p>
        <p>Análisis de Costos de Transporte de Productos Agrícolas</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td width="50%"><strong>Periodo:</strong> {{ $filtros['periodo'] }}</td>
                <td width="50%"><strong>Fecha de generación:</strong> {{ $fecha_generacion }}</td>
            </tr>
            <tr>
                <td><strong>Tipo de vehículo:</strong> {{ $filtros['tipo_vehiculo'] }}</td>
                <td><strong>Ruta:</strong> {{ $filtros['ruta'] }}</td>
            </tr>
        </table>
    </div>

    <div class="estadisticas">
        <h3>📊 ESTADÍSTICAS GENERALES DE TRANSPORTE</h3>
        <table>
            <tr>
                <td>Total de rutas analizadas:</td>
                <td><strong>{{ $estadisticas['total_rutas'] }}</strong></td>
                <td>Total de viajes realizados:</td>
                <td><strong>{{ $estadisticas['total_viajes'] }}</strong></td>
            </tr>
            <tr>
                <td>Costo promedio por viaje:</td>
                <td><strong>S/. {{ number_format($estadisticas['costo_promedio_general'], 2) }}</strong></td>
                <td>Distancia total recorrida:</td>
                <td><strong>{{ number_format($estadisticas['distancia_total'], 0) }} km</strong></td>
            </tr>
        </table>
    </div>

    <h3 style="font-size: 12px; color: #1565C0; margin: 15px 0 10px 0;">DETALLE DE RUTAS Y COSTOS</h3>

    <table class="datos">
        <thead>
            <tr>
                <th width="8%">Fecha</th>
                <th width="12%">Tipo Vehículo</th>
                <th width="18%">Ruta</th>
                <th width="12%">Origen</th>
                <th width="12%">Destino</th>
                <th width="10%">Producto</th>
                <th width="8%">Distancia</th>
                <th width="10%">Costo Viaje</th>
                <th width="8%">Costo/Km</th>
                <th width="6%">Viajes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos as $item)
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                <td>{{ $item->tipo_vehiculo }}</td>
                <td><strong>{{ $item->ruta }}</strong></td>
                <td>{{ $item->departamento_origen }}</td>
                <td>{{ $item->departamento_destino }}</td>
                <td>{{ $item->producto_transportado }}</td>
                <td class="text-right">{{ number_format($item->distancia_km, 0) }} km</td>
                <td class="text-right"><strong>S/. {{ number_format($item->costo_promedio, 2) }}</strong></td>
                <td class="text-right">S/. {{ number_format($item->costo_por_km, 2) }}</td>
                <td class="text-center">{{ $item->num_viajes }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">No se encontraron registros de transporte</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #E3F2FD; font-weight: bold;">
                <td colspan="6" class="text-right">TOTALES:</td>
                <td class="text-right">{{ number_format($datos->sum('distancia_km'), 0) }} km</td>
                <td class="text-right">S/. {{ number_format($datos->sum('costo_promedio'), 2) }}</td>
                <td colspan="1"></td>
                <td class="text-center">{{ $datos->sum('num_viajes') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 20px; padding: 10px; background-color: #FFF9C4; border-left: 4px solid #F9A825;">
        <h4 style="font-size: 10px; margin-bottom: 5px; color: #F57F17;">📌 NOTAS IMPORTANTES:</h4>
        <ul style="font-size: 8px; margin-left: 15px; line-height: 1.4;">
            <li>Los costos incluyen combustible, peajes y otros gastos operativos</li>
            <li>Las distancias son referenciales y pueden variar según la ruta específica</li>
            <li>El costo por kilómetro permite comparar la eficiencia entre diferentes rutas</li>
            <li>Los datos provienen de encuestas realizadas a transportistas en el periodo indicado</li>
        </ul>
    </div>

    <div class="footer">
        <p>SIEA - Sistema Integrado de Estadística Agraria | Página {PAGE_NUM} de {PAGE_COUNT}</p>
        <p>Documento generado el {{ $fecha_generacion }}</p>
    </div>
</body>
</html>
