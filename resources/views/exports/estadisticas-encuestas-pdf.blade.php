<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas de Encuestas - SIEA</title>
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
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #4CAF50;
        }

        .header h1 {
            color: #4CAF50;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        .filtros {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .filtros h3 {
            font-size: 12px;
            margin-bottom: 5px;
            color: #4CAF50;
        }

        .filtros p {
            font-size: 9px;
            margin: 2px 0;
        }

        .estadisticas-resumen {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .stat-box {
            text-align: center;
            padding: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 5px;
            flex: 1;
            margin: 0 5px;
        }

        .stat-box h4 {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .stat-box .numero {
            font-size: 24px;
            font-weight: bold;
        }

        .seccion {
            margin-bottom: 20px;
        }

        .seccion h3 {
            font-size: 12px;
            color: #4CAF50;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }

        thead {
            background-color: #4CAF50;
            color: white;
        }

        th, td {
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            font-weight: bold;
            font-size: 9px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-validado {
            background-color: #4CAF50;
            color: white;
        }

        .badge-enviado {
            background-color: #2196F3;
            color: white;
        }

        .badge-borrador {
            background-color: #9E9E9E;
            color: white;
        }

        .badge-rechazado {
            background-color: #F44336;
            color: white;
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
        <h1>📊 Estadísticas de Encuestas SIEA</h1>
        <p>Dirección de Estadística e Información Agraria</p>
        <p><strong>Fecha de generación:</strong> {{ $fecha_generacion }}</p>
    </div>

    @if(count($filtros) > 0)
    <div class="filtros">
        <h3>🔍 Filtros Aplicados</h3>
        @if(isset($filtros['anio']))
            <p><strong>Año:</strong> {{ $filtros['anio'] }}</p>
        @endif
        @if(isset($filtros['mes']))
            <p><strong>Mes:</strong> {{ $filtros['mes'] }}</p>
        @endif
        @if(isset($filtros['region']))
            <p><strong>Región:</strong> {{ $filtros['region'] }}</p>
        @endif
        @if(isset($filtros['provincia']))
            <p><strong>Provincia:</strong> {{ $filtros['provincia'] }}</p>
        @endif
        @if(isset($filtros['estado']))
            <p><strong>Estado:</strong> {{ ucfirst($filtros['estado']) }}</p>
        @endif
        @if(isset($filtros['tipo_formulario']))
            <p><strong>Tipo Formulario:</strong> {{ $filtros['tipo_formulario'] }}</p>
        @endif
    </div>
    @endif

    <div class="seccion">
        <h3>📈 Resumen General</h3>
        <table>
            <thead>
                <tr>
                    <th>Total Encuestas</th>
                    <th>Validadas</th>
                    <th>Enviadas</th>
                    <th>Borradores</th>
                    <th>Rechazadas</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $estadisticas['total'] }}</strong></td>
                    <td>{{ $estadisticas['por_estado']['validado'] ?? 0 }}</td>
                    <td>{{ $estadisticas['por_estado']['enviado'] ?? 0 }}</td>
                    <td>{{ $estadisticas['por_estado']['borrador'] ?? 0 }}</td>
                    <td>{{ $estadisticas['por_estado']['rechazado'] ?? 0 }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="seccion">
        <h3>📋 Por Tipo de Formulario</h3>
        <table>
            <thead>
                <tr>
                    <th>Tipo Formulario</th>
                    <th>Cantidad</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estadisticas['por_tipo'] as $tipo => $cantidad)
                <tr>
                    <td>{{ $tipo }}</td>
                    <td>{{ $cantidad }}</td>
                    <td>{{ $estadisticas['total'] > 0 ? round(($cantidad / $estadisticas['total']) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="seccion">
        <h3>📝 Detalle de Encuestas</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Región</th>
                    <th>Provincia</th>
                    <th>Estado</th>
                    <th>Encuestador</th>
                    <th>Supervisor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($encuestas as $encuesta)
                <tr>
                    <td>{{ $encuesta->id }}</td>
                    <td>{{ $encuesta->tipo_formulario }}</td>
                    <td>{{ \Carbon\Carbon::parse($encuesta->fecha_recoleccion)->format('d/m/Y') }}</td>
                    <td>{{ $encuesta->region }}</td>
                    <td>{{ $encuesta->provincia }}</td>
                    <td>
                        <span class="badge badge-{{ $encuesta->estado }}">
                            {{ ucfirst($encuesta->estado) }}
                        </span>
                    </td>
                    <td>{{ $encuesta->encuestador ? $encuesta->encuestador->nombre_completo : 'N/A' }}</td>
                    <td>{{ $encuesta->supervisor ? $encuesta->supervisor->nombre_completo : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Sistema de Información Agraria - DRA © {{ date('Y') }} | Página {PAGENO} de {nb}</p>
    </div>
</body>
</html>
