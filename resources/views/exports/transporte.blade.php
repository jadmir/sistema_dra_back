<table>
    <thead>
        <tr>
            <th colspan="10" style="background-color: #1565C0; color: white; font-weight: bold; font-size: 16px; padding: 10px;">
                REPORTE DE TRANSPORTE - FORMULARIO F-14
            </th>
        </tr>
        <tr>
            <th colspan="10" style="background-color: #E3F2FD; padding: 5px;">
                <strong>Sistema:</strong> SIEA - Sistema Integrado de Estadística Agraria
            </th>
        </tr>
        <tr>
            <th colspan="10" style="background-color: #E3F2FD; padding: 5px;">
                <strong>Periodo:</strong> {{ $filtros['periodo'] ?? 'Todos los periodos' }}
            </th>
        </tr>
        <tr>
            <th colspan="10" style="background-color: #E3F2FD; padding: 5px;">
                <strong>Tipo de Vehículo:</strong> {{ $filtros['tipo_vehiculo'] ?? 'Todos' }} |
                <strong>Ruta:</strong> {{ $filtros['origen'] ?? 'Todos' }} - {{ $filtros['destino'] ?? 'Todos' }}
            </th>
        </tr>
        <tr>
            <th colspan="10" style="background-color: #E3F2FD; padding: 5px;">
                <strong>Fecha de generación:</strong> {{ $fecha_generacion }}
            </th>
        </tr>
        <tr>
            <th colspan="10"></th>
        </tr>
        <tr style="background-color: #1976D2; color: white; font-weight: bold;">
            <th style="border: 1px solid #000; padding: 8px;">Fecha</th>
            <th style="border: 1px solid #000; padding: 8px;">Tipo Vehículo</th>
            <th style="border: 1px solid #000; padding: 8px;">Ruta</th>
            <th style="border: 1px solid #000; padding: 8px;">Origen</th>
            <th style="border: 1px solid #000; padding: 8px;">Destino</th>
            <th style="border: 1px solid #000; padding: 8px;">Producto</th>
            <th style="border: 1px solid #000; padding: 8px;">Distancia (km)</th>
            <th style="border: 1px solid #000; padding: 8px;">Costo Promedio</th>
            <th style="border: 1px solid #000; padding: 8px;">Costo/Km</th>
            <th style="border: 1px solid #000; padding: 8px;">N° Viajes</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $item)
        <tr>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->fecha }}</td>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->tipo_vehiculo }}</td>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->ruta }}</td>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->departamento_origen }}</td>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->departamento_destino }}</td>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->producto_transportado }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($item->distancia_km, 0) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">S/. {{ number_format($item->costo_promedio, 2) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">S/. {{ number_format($item->costo_por_km, 2) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ $item->num_viajes }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="10" style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                No se encontraron registros para los filtros seleccionados
            </td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="background-color: #E3F2FD; font-weight: bold;">
            <td colspan="6" style="border: 1px solid #000; padding: 8px;">TOTAL DE REGISTROS:</td>
            <td colspan="4" style="border: 1px solid #000; padding: 8px;">{{ count($datos) }}</td>
        </tr>
        <tr style="background-color: #E3F2FD; font-weight: bold;">
            <td colspan="6" style="border: 1px solid #000; padding: 8px;">TOTAL VIAJES:</td>
            <td colspan="4" style="border: 1px solid #000; padding: 8px;">{{ $datos->sum('num_viajes') }}</td>
        </tr>
    </tfoot>
</table>
