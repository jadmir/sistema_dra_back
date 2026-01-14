<table>
    <thead>
        <tr>
            <th colspan="14" style="background-color: #2E7D32; color: white; font-weight: bold; font-size: 16px; padding: 10px;">
                REPORTE DE ANÁLISIS DE PRECIOS - {{ $tipo === 'F-6' ? 'AGROQUÍMICOS (F-6)' : 'TRANSPORTE (F-14)' }}
            </th>
        </tr>
        <tr>
            <th colspan="14" style="background-color: #E8F5E9; padding: 5px;">
                <strong>Sistema:</strong> SIEA - Sistema Integrado de Estadística Agraria
            </th>
        </tr>
        <tr>
            <th colspan="14" style="background-color: #E8F5E9; padding: 5px;">
                <strong>Periodo:</strong> {{ $filtros['periodo'] ?? 'Todos los periodos' }}
            </th>
        </tr>
        <tr>
            <th colspan="14" style="background-color: #E8F5E9; padding: 5px;">
                <strong>Región:</strong> {{ $filtros['region'] ?? 'Todas las regiones' }} |
                <strong>Provincia:</strong> {{ $filtros['provincia'] ?? 'Todas las provincias' }}
            </th>
        </tr>
        <tr>
            <th colspan="14" style="background-color: #E8F5E9; padding: 5px;">
                <strong>Fecha de generación:</strong> {{ $fecha_generacion }}
            </th>
        </tr>
        <tr>
            <th colspan="14"></th>
        </tr>
        <tr style="background-color: #388E3C; color: white; font-weight: bold;">
            <th style="border: 1px solid #000; padding: 8px;">Fecha</th>
            <th style="border: 1px solid #000; padding: 8px;">Producto</th>
            <th style="border: 1px solid #000; padding: 8px;">Categoría</th>
            <th style="border: 1px solid #000; padding: 8px;">Precio Promedio</th>
            <th style="border: 1px solid #000; padding: 8px;">Precio Mínimo</th>
            <th style="border: 1px solid #000; padding: 8px;">Precio Máximo</th>
            <th style="border: 1px solid #000; padding: 8px;">Mediana</th>
            <th style="border: 1px solid #000; padding: 8px;">Desv. Est.</th>
            <th style="border: 1px solid #000; padding: 8px;">Coef. Variación</th>
            <th style="border: 1px solid #000; padding: 8px;">Precio Anterior</th>
            <th style="border: 1px solid #000; padding: 8px;">Variación %</th>
            <th style="border: 1px solid #000; padding: 8px;">Tendencia</th>
            <th style="border: 1px solid #000; padding: 8px;">N° Registros</th>
            <th style="border: 1px solid #000; padding: 8px;">Ubicación</th>
        </tr>
    </thead>
    <tbody>
        @forelse($datos as $item)
        <tr>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->fecha }}</td>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->producto_nombre }}</td>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->producto_categoria }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($item->precio_promedio, 2) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($item->precio_minimo, 2) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($item->precio_maximo, 2) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($item->precio_mediana, 2) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($item->desviacion_estandar, 2) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($item->coeficiente_variacion, 2) }}%</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($item->precio_periodo_anterior, 2) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($item->variacion_porcentual, 2) }}%</td>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ strtoupper($item->tendencia) }}</td>
            <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ $item->num_registros }}</td>
            <td style="border: 1px solid #ccc; padding: 5px;">{{ $item->provincia }}, {{ $item->distrito }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="14" style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                No se encontraron registros para los filtros seleccionados
            </td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="background-color: #E8F5E9; font-weight: bold;">
            <td colspan="3" style="border: 1px solid #000; padding: 8px;">TOTAL DE REGISTROS:</td>
            <td colspan="11" style="border: 1px solid #000; padding: 8px;">{{ count($datos) }}</td>
        </tr>
    </tfoot>
</table>
