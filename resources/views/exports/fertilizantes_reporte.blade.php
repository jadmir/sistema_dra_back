<table>
    <thead>
        <!-- ENCABEZADO SIMPLE -->
        <tr>
            <th colspan="12">SIEA - DGESEP - DEIA</th>
        </tr>
        <tr>
            <th colspan="12">PRECIOS DE PRINCIPALES FERTILIZANTES Y ABONOS ORGÁNICOS (S/.)</th>
        </tr>
        <tr>
            <th colspan="12">FORMULARIO F-4</th>
        </tr>

        <!-- INFORMACIÓN DE UBICACIÓN Y PERÍODO -->
        <tr>
            <th colspan="6">Región: {{ $filtros['region'] }}</th>
            <th colspan="6">Año: {{ $filtros['anio'] }}</th>
        </tr>
        <tr>
            <th colspan="6">Provincia: {{ $filtros['provincia'] }}</th>
            <th colspan="6">Mes: {{ $filtros['mes_nombre'] }}</th>
        </tr>
        <tr>
            <th colspan="12">Distrito: {{ $filtros['distrito'] }}</th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                2. Provincia
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['provincia'] }}
            </th>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-left: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Mes
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['mes'] }} - {{ $filtros['mes_nombre'] }}
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                3. Distrito
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['distrito'] }}
            </th>
            <th colspan="6" style="border: 2px solid #000; border-top: none; border-left: none;"></th>
        </tr>

        <!-- Espacio -->
        <tr><th colspan="12" style="height: 10px;"></th></tr>

        <!-- RESUMEN ESTADÍSTICO -->
        <tr>
            <th colspan="12" style="background-color: #FFC107; border: 2px solid #8B4513; font-weight: bold; font-size: 11px; padding: 5px; text-align: center;">
                RESUMEN ESTADÍSTICO DEL PERÍODO
            </th>
        </tr>
        <tr>
            <th colspan="3" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                Total Encuestas
            </th>
            <th colspan="3" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                Total Registros
            </th>
            <th colspan="3" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                Productos Distintos
            </th>
            <th colspan="3" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                Precio Promedio
            </th>
        </tr>
        <tr>
            <th colspan="3" style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 14px; font-weight: bold; color: #2196F3;">
                {{ $estadisticas['total_encuestas'] }}
            </th>
            <th colspan="3" style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 14px; font-weight: bold; color: #4CAF50;">
                {{ $estadisticas['total_registros'] }}
            </th>
            <th colspan="3" style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 14px; font-weight: bold; color: #FF9800;">
                {{ $estadisticas['productos_distintos'] }}
            </th>
            <th colspan="3" style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 14px; font-weight: bold; color: #9C27B0;">
                S/. {{ number_format($estadisticas['precio_promedio'], 2) }}
            </th>
        </tr>

        <!-- Espacio -->
        <tr><th colspan="12" style="height: 10px;"></th></tr>

        <!-- SECCIÓN III: TABLA DE PRECIOS -->
        <tr>
            <th colspan="12" style="background-color: #FFF; border: 2px solid #000; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                III. Información de precios de principales fertilizantes y abonos orgánicos
            </th>
        </tr>

        <!-- Encabezados de columnas -->
        <tr>
            <th colspan="2" rowspan="2" style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center; vertical-align: middle;">
                TIPO
            </th>
            <th rowspan="2" style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center; vertical-align: middle;">
                PRODUCTO
            </th>
            <th rowspan="2" style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center; vertical-align: middle;">
                ENVASE<br>COMERCIAL
            </th>
            <th colspan="6" style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center;">
                PRECIO PROMEDIO EN PRINCIPALES CASAS COMERCIALES (S/.)
            </th>
            <th rowspan="2" style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center; vertical-align: middle;">
                PRECIO<br>PROMEDIO<br>(S/.)
            </th>
            <th rowspan="2" style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center; vertical-align: middle;">
                N°<br>REG.
            </th>
        </tr>
        <tr>
            @for($i = 1; $i <= 6; $i++)
            <th style="background-color: #FFFDE7; border: 1px solid #000; font-size: 7px; padding: 3px; text-align: center;">
                Casa {{ $i }}
            </th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @php
            $categorias = [
                'NITROGENADOS' => 'FERTILIZANTES QUÍMICOS NITROGENADOS',
                'FOSFATADOS' => 'FOSFATADOS',
                'POTASICOS' => 'POTASICOS',
                'COMPUESTOS' => 'COMPUESTOS',
                'ORGANICOS' => 'ABONOS ORGÁNICOS'
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
                    <th colspan="2" style="background-color: #FFF3E0; border: 2px solid #8B4513; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                        {{ $key == 'ORGANICOS' ? '2. ' : '' }}{{ $nombreCategoria }}
                    </th>
                    <th colspan="10" style="border: 2px solid #8B4513; background-color: #FFFDE7;"></th>
                </tr>

                <!-- Productos de la categoría -->
                @foreach($productos as $producto)
                <tr>
                    <td colspan="2" style="border: 1px solid #000; padding: 5px; text-align: left; font-size: 8px;">
                        {{ $key }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: left; font-size: 8px;">
                        <strong>{{ $producto['nombre'] }}</strong>
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 8px;">
                        Bolsa x 50 Kg
                    </td>
                    <!-- Casas comerciales (simulado con precios) -->
                    <td style="border: 1px solid #000; padding: 5px; text-align: right; font-size: 8px;">
                        S/. {{ number_format($producto['precio_minimo'], 2) }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: right; font-size: 8px;">
                        S/. {{ number_format(($producto['precio_minimo'] + $producto['precio_promedio']) / 2, 2) }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: right; font-size: 8px;">
                        S/. {{ number_format($producto['precio_promedio'], 2) }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: right; font-size: 8px;">
                        S/. {{ number_format(($producto['precio_promedio'] + $producto['precio_maximo']) / 2, 2) }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: right; font-size: 8px;">
                        S/. {{ number_format($producto['precio_maximo'], 2) }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: right; font-size: 8px;">
                        -
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: right; font-size: 9px; font-weight: bold; background-color: #E8F5E9;">
                        S/. {{ number_format($producto['precio_promedio'], 2) }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 8px; font-weight: bold;">
                        {{ $producto['num_registros'] }}
                    </td>
                </tr>
                @endforeach
            @endif
        @endforeach

        <!-- Espacio -->
        <tr><th colspan="12" style="height: 10px;"></th></tr>

        <!-- TOTALES -->
        <tr>
            <th colspan="10" style="background-color: #FFE082; border: 2px solid #000; font-weight: bold; padding: 5px; text-align: right; font-size: 10px;">
                PRECIO PROMEDIO GENERAL:
            </th>
            <th style="background-color: #E8F5E9; border: 2px solid #000; font-weight: bold; padding: 5px; text-align: right; font-size: 11px;">
                S/. {{ number_format($estadisticas['precio_promedio'], 2) }}
            </th>
            <th style="background-color: #FFF9C4; border: 2px solid #000; font-weight: bold; padding: 5px; text-align: center; font-size: 11px;">
                {{ $estadisticas['total_registros'] }}
            </th>
        </tr>

        <!-- Espacio -->
        <tr><th colspan="12" style="height: 15px;"></th></tr>

        <!-- SECCIÓN IV: OBSERVACIONES -->
        <tr>
            <th colspan="12" style="border: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                IV. Observaciones
            </th>
        </tr>
        <tr>
            <td colspan="12" style="border: 2px solid #000; border-top: none; padding: 20px; height: 40px; font-size: 8px; color: #666;">
                Los precios mostrados corresponden a promedios calculados de múltiples encuestas realizadas en el período indicado.
            </td>
        </tr>

        <!-- Espacio -->
        <tr><th colspan="12" style="height: 10px;"></th></tr>

        <!-- SECCIÓN V: FIRMAS -->
        <tr>
            <th colspan="12" style="border: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                V. Del encuestador y supervisor
            </th>
        </tr>
        <tr>
            <th colspan="6" style="border: 2px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                ENCUESTADOR
            </th>
            <th colspan="6" style="border: 2px solid #000; border-top: 1px solid #000; border-left: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                SUPERVISOR
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; font-weight: bold; font-size: 8px; padding: 3px; text-align: left;">
                Nombres
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 8px; padding: 3px;">
            </th>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-left: none; font-weight: bold; font-size: 8px; padding: 3px; text-align: left;">
                Nombres
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 8px; padding: 3px;">
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; font-weight: bold; font-size: 8px; padding: 3px; text-align: left;">
                Apellidos
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 8px; padding: 3px;">
            </th>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-left: none; font-weight: bold; font-size: 8px; padding: 3px; text-align: left;">
                Apellidos
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 8px; padding: 3px;">
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; font-weight: bold; font-size: 8px; padding: 3px; text-align: left;">
                Cargo
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 8px; padding: 3px;">
            </th>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-left: none; font-weight: bold; font-size: 8px; padding: 3px; text-align: left;">
                Fecha de supervisión
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 8px; padding: 3px;">
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-weight: bold; font-size: 8px; padding: 3px; text-align: left;">
                Firma
            </th>
        </tr>

        <!-- Footer -->
        <tr><th colspan="12" style="height: 10px;"></th></tr>
        <tr>
            <td colspan="12" style="font-size: 9px; padding: 8px; text-align: right; font-weight: bold; background-color: #FFFDE7; border-top: 2px solid #FFC107;">
                F2-EISA-DGESEP-DEIA
            </td>
        </tr>
    </tbody>
</table>
