<table>
    <thead>
        <!-- ENCABEZADO -->
        <tr>
            <th colspan="2" rowspan="3" style="background-color: #FFC107; border: 2px solid #000;">
                <strong style="font-size: 16px;">¡SIEA</strong><br>
                <span style="font-size: 8px;">Sistema Integrado de Estadística Agraria</span>
            </th>
            <th colspan="9" style="background-color: #FFC107; border: 2px solid #000; text-align: center;">
                <strong style="font-size: 11px;">Dirección General de Estadística, Seguimiento y Evaluación de Políticas - DGESEP</strong><br>
                <span style="font-size: 9px;">Dirección de Estadística e Información Agraria - DEIA</span><br>
                <strong style="font-size: 12px;">PRECIOS DE PRINCIPALES AGROQUÍMICOS (S/.)</strong>
            </th>
            <th rowspan="3" style="background-color: #FFC107; border: 3px solid #8B4513; text-align: center; vertical-align: middle;">
                <strong style="font-size: 36px; color: #8B4513;">F-6</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <!-- UBICACIÓN POLÍTICA Y PERIODO -->
        <tr>
            <th colspan="4" style="background-color: #FFF3E0; border: 1px solid #333; text-align: left; padding: 5px;">
                <strong>I. Ubicación política</strong>
            </th>
            <th colspan="7" style="background-color: #FFF3E0; border: 1px solid #333; text-align: left; padding: 5px;">
                <strong>II. Año y mes de referencia</strong>
            </th>
        </tr>
        <tr>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">Región</th>
            <td colspan="3" style="border: 1px solid #333;">{{ $filtros['region'] }}</td>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">Año</th>
            <td style="border: 1px solid #333;">{{ $filtros['anio'] }}</td>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">Mes</th>
            <td colspan="4" style="border: 1px solid #333;">{{ $filtros['mes'] }} - {{ $filtros['mes_nombre'] }}</td>
        </tr>
        <tr>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">Provincia</th>
            <td colspan="3" style="border: 1px solid #333;">{{ $filtros['provincia'] }}</td>
            <th colspan="7" style="background-color: #FFFDE7; border: 1px solid #333;">Fecha generación: {{ $fecha_generacion }}</th>
        </tr>
        <tr>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">Distrito</th>
            <td colspan="10" style="border: 1px solid #333;">{{ $filtros['distrito'] }}</td>
        </tr>

        <!-- RESUMEN ESTADÍSTICO -->
        <tr>
            <th colspan="12" style="background-color: #FFC107; border: 2px solid #8B4513; text-align: center; padding: 8px;">
                <strong style="font-size: 11px;">RESUMEN ESTADÍSTICO DEL PERÍODO</strong>
            </th>
        </tr>
        <tr>
            <th colspan="3" style="background-color: #E3F2FD; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Total Encuestas</strong><br>
                <span style="font-size: 14px; color: #2196F3;">{{ $estadisticas['total_encuestas'] }}</span>
            </th>
            <th colspan="3" style="background-color: #E8F5E9; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Total Registros</strong><br>
                <span style="font-size: 14px; color: #4CAF50;">{{ $estadisticas['total_registros'] }}</span>
            </th>
            <th colspan="3" style="background-color: #FFF3E0; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Productos Distintos</strong><br>
                <span style="font-size: 14px; color: #FF9800;">{{ $estadisticas['productos_distintos'] }}</span>
            </th>
            <th colspan="3" style="background-color: #F3E5F5; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Precio Promedio</strong><br>
                <span style="font-size: 14px; color: #9C27B0;">S/. {{ number_format($estadisticas['precio_promedio'], 2) }}</span>
            </th>
        </tr>

        <!-- ENCABEZADO DE TABLA -->
        <tr>
            <th colspan="12" style="background-color: #FFC107; border: 2px solid #8B4513; text-align: center; padding: 5px;">
                <strong>III. Información de precios de principales agroquímicos</strong>
            </th>
        </tr>
        <tr>
            <th rowspan="2" style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">TIPO</th>
            <th rowspan="2" style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">AGROQUÍMICO</th>
            <th rowspan="2" style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">ENVASE COMERCIAL</th>
            <th colspan="6" style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">PRECIOS EN PRINCIPALES CASAS COMERCIALES (S/.)</th>
            <th rowspan="2" style="background-color: #E8F5E9; border: 1px solid #333; text-align: center; font-weight: bold;">PRECIO<br>PROMEDIO<br>(S/.)</th>
            <th rowspan="2" style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">N° REG.</th>
        </tr>
        <tr>
            <th style="background-color: #FFF9C4; border: 1px solid #333; text-align: center; font-size: 8px;">Casa 1</th>
            <th style="background-color: #FFF9C4; border: 1px solid #333; text-align: center; font-size: 8px;">Casa 2</th>
            <th style="background-color: #FFF9C4; border: 1px solid #333; text-align: center; font-size: 8px;">Casa 3</th>
            <th style="background-color: #FFF9C4; border: 1px solid #333; text-align: center; font-size: 8px;">Casa 4</th>
            <th style="background-color: #FFF9C4; border: 1px solid #333; text-align: center; font-size: 8px;">Casa 5</th>
            <th style="background-color: #FFF9C4; border: 1px solid #333; text-align: center; font-size: 8px;">Casa 6</th>
        </tr>

        @php
            $categorias = [
                'ACARICIDAS' => 'ACARICIDAS',
                'ADHERENTES' => 'ADHERENTES',
                'FUNGICIDAS' => 'FUNGICIDAS',
                'HERBICIDAS' => 'HERBICIDAS',
                'INSECTICIDAS' => 'INSECTICIDAS',
                'NUTRIENTES FOLIARES' => 'NUTRIENTES FOLIARES',
                'REGULADORES DE CRECIMIENTO' => 'REGULADORES DE CRECIMIENTO'
            ];

            $agrupadoPorCategoria = collect($datos)->groupBy('categoria');
        @endphp

        @foreach($categorias as $key => $nombreCategoria)
            @php
                $productos = $agrupadoPorCategoria->get($key, collect());
            @endphp

            @if($productos->isNotEmpty())
                <!-- Fila de categoría -->
                <tr>
                    <th colspan="3" style="background-color: #FFF3E0; border: 2px solid #8B4513; text-align: left; font-weight: bold; padding: 5px;">
                        {{ $nombreCategoria }}
                    </th>
                    <th colspan="9" style="background-color: #FFF3E0; border: 2px solid #8B4513;"></th>
                </tr>

                <!-- Productos de la categoría -->
                @foreach($productos as $producto)
                <tr>
                    <td colspan="2" style="border: 1px solid #333; text-align: left; font-size: 9px;">
                        {{ $key }}
                    </td>
                    <td style="border: 1px solid #333; text-align: left; font-weight: bold;">
                        {{ $producto['nombre'] }}
                    </td>
                    <td style="border: 1px solid #333; text-align: center; font-size: 8px;">
                        {{ $producto['envase'] }}
                    </td>
                    <!-- 6 columnas para casas comerciales (mostramos los precios min/max/promedio distribuidos) -->
                    <td style="border: 1px solid #333; text-align: right;">
                        S/. {{ number_format($producto['precio_minimo'], 2) }}
                    </td>
                    <td style="border: 1px solid #333; text-align: right;">
                        ...................
                    </td>
                    <td style="border: 1px solid #333; text-align: right;">
                        S/. {{ number_format($producto['precio_promedio'], 2) }}
                    </td>
                    <td style="border: 1px solid #333; text-align: right;">
                        ...................
                    </td>
                    <td style="border: 1px solid #333; text-align: right;">
                        S/. {{ number_format($producto['precio_maximo'], 2) }}
                    </td>
                    <td style="border: 1px solid #333; text-align: right;">
                        ...................
                    </td>
                    <!-- Precio promedio destacado -->
                    <td style="border: 1px solid #333; text-align: right; background-color: #E8F5E9; font-weight: bold;">
                        S/. {{ number_format($producto['precio_promedio'], 2) }}
                    </td>
                    <!-- Número de registros -->
                    <td style="border: 1px solid #333; text-align: center; font-weight: bold;">
                        {{ $producto['num_registros'] }}
                    </td>
                </tr>
                @endforeach
            @endif
        @endforeach

        <!-- FILA DE TOTALES -->
        <tr>
            <th colspan="9" style="background-color: #FFE082; border: 2px solid #333; text-align: right; font-weight: bold; padding: 5px;">
                PRECIO PROMEDIO GENERAL:
            </th>
            <th style="background-color: #E8F5E9; border: 2px solid #333; text-align: right; font-weight: bold; font-size: 11px;">
                S/. {{ number_format($estadisticas['precio_promedio'], 2) }}
            </th>
            <th style="background-color: #FFF9C4; border: 2px solid #333; text-align: center; font-weight: bold; font-size: 11px;">
                {{ $estadisticas['total_registros'] }}
            </th>
        </tr>

        <!-- OBSERVACIONES -->
        <tr>
            <th colspan="12" style="background-color: #FFFDE7; border: 1px solid #333; text-align: left; padding: 5px;">
                <strong>IV. Observaciones</strong>
            </th>
        </tr>
        <tr>
            <td colspan="12" style="border: 1px solid #333; height: 60px; vertical-align: top; padding: 5px;">
                * Productos no registrados en SENASA
            </td>
        </tr>

        <!-- FIRMAS -->
        <tr>
            <th colspan="12" style="background-color: #FFFDE7; border: 1px solid #333; text-align: left; padding: 5px;">
                <strong>V. Del encuestador y supervisor</strong>
            </th>
        </tr>
        <tr>
            <th colspan="6" style="background-color: #F5F5F5; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>ENCUESTADOR</strong>
            </th>
            <th colspan="6" style="background-color: #F5F5F5; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>SUPERVISOR</strong>
            </th>
        </tr>
        <tr>
            <td colspan="6" style="border: 1px solid #333; padding: 5px; height: 50px;">
                <strong>Nombres:</strong> ......................................<br>
                <strong>Apellidos:</strong> ......................................<br>
                <strong>Cargo:</strong> ......................................
            </td>
            <td colspan="6" style="border: 1px solid #333; padding: 5px; height: 50px;">
                <strong>Nombres:</strong> ......................................<br>
                <strong>Apellidos:</strong> ......................................<br>
                <strong>Cargo:</strong> ......................................<br>
                <strong>Fecha de Supervisión:</strong> ....../....../......<br>
                <strong>Firma:</strong> ......................................
            </td>
        </tr>

        <!-- FOOTER -->
        <tr>
            <td colspan="12" style="border: none; text-align: right; padding: 5px; font-size: 8px; color: #666;">
                F3-EISA-DGESEP-DEIA
            </td>
        </tr>
    </tbody>
</table>
