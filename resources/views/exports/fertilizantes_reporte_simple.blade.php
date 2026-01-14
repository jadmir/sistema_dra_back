@php
    $categorias = [
        'NITROGENADOS' => 'FERTILIZANTES QUÍMICOS NITROGENADOS',
        'FOSFATADOS' => 'FOSFATADOS',
        'POTASICOS' => 'POTÁSICOS',
        'COMPUESTOS' => 'COMPUESTOS',
        'ORGANICOS' => 'ABONOS ORGÁNICOS'
    ];
    $agrupadoPorCategoria = collect($datos)->groupBy('categoria');
@endphp
<table>
    <thead>
        <tr>
            <th colspan="5">SIEA - DGESEP - DEIA - FORMULARIO F-4</th>
        </tr>
        <tr>
            <th colspan="5">PRECIOS DE PRINCIPALES FERTILIZANTES Y ABONOS ORGÁNICOS (S/.)</th>
        </tr>
        <tr>
            <th colspan="2">Región: {{ $filtros['region'] }}</th>
            <th colspan="3">Año: {{ $filtros['anio'] }} - Mes: {{ $filtros['mes_nombre'] }}</th>
        </tr>
        <tr>
            <th colspan="2">Provincia: {{ $filtros['provincia'] }}</th>
            <th colspan="3">Distrito: {{ $filtros['distrito'] }}</th>
        </tr>
        <tr>
            <th colspan="5"></th>
        </tr>
        <tr>
            <th>PRODUCTO</th>
            <th>PRECIO PROMEDIO</th>
            <th>PRECIO MÍNIMO</th>
            <th>PRECIO MÁXIMO</th>
            <th>REGISTROS</th>
        </tr>
    </thead>
    <tbody>
        @if($datos->isEmpty())
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                    No hay datos disponibles para el período seleccionado
                </td>
            </tr>
        @else
            @foreach($categorias as $codigo => $nombre)
                @if(isset($agrupadoPorCategoria[$codigo]) && $agrupadoPorCategoria[$codigo]->isNotEmpty())
                    <tr>
                        <td colspan="5"><strong>{{ $nombre }}</strong></td>
                    </tr>
                    @foreach($agrupadoPorCategoria[$codigo] as $item)
                        <tr>
                            <td>{{ $item['nombre'] }}</td>
                            <td>S/. {{ number_format($item['precio_promedio'], 2) }}</td>
                            <td>S/. {{ number_format($item['precio_minimo'], 2) }}</td>
                            <td>S/. {{ number_format($item['precio_maximo'], 2) }}</td>
                            <td>{{ $item['num_registros'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5"></td>
                    </tr>
                @endif
            @endforeach
        @endif

        <!-- Footer con estadísticas -->
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="5"><strong>ESTADÍSTICAS DEL PERÍODO</strong></td>
        </tr>
        <tr>
            <td>Total Encuestas:</td>
            <td>{{ $estadisticas['total_encuestas'] }}</td>
            <td>Total Registros:</td>
            <td colspan="2">{{ $estadisticas['total_registros'] }}</td>
        </tr>
        <tr>
            <td>Productos Distintos:</td>
            <td>{{ $estadisticas['productos_distintos'] }}</td>
            <td>Precio Promedio:</td>
            <td colspan="2">S/. {{ number_format($estadisticas['precio_promedio'], 2) }}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="5">Generado: {{ $fecha_generacion }}</td>
        </tr>
    </tbody>
</table>
