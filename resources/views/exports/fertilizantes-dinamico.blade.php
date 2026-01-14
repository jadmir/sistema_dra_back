<table>
    <thead>
        <!-- Encabezado amarillo con logo y título -->
        <tr>
            <th colspan="1" style="background-color: #FFC107; color: #8B4513; font-weight: bold; font-size: 20px; padding: 8px; border: 2px solid #000; text-align: left; vertical-align: middle;">
                ¡SIEA
            </th>
            <th colspan="10" style="background-color: #FFC107; color: #8B4513; font-weight: bold; font-size: 11px; padding: 8px; border: 2px solid #000; border-left: none; text-align: center;">
                Dirección General de Estadística, Seguimiento y Evaluación de Políticas-DGESEP
            </th>
            <th rowspan="3" style="background-color: #FFC107; color: #8B4513; font-weight: bold; font-size: 28px; padding: 8px; border: 2px solid #8B4513; text-align: center; vertical-align: middle; writing-mode: tb-rl; transform: rotate(180deg);">
                F-4
            </th>
        </tr>
        <tr>
            <th colspan="11" style="background-color: #FFC107; color: #000; font-weight: bold; font-size: 10px; padding: 5px; border-left: 2px solid #000; border-right: 2px solid #000; text-align: center;">
                Dirección de Estadística e Información Agraria - DEIA
            </th>
        </tr>
        <tr>
            <th colspan="11" style="background-color: #FFC107; color: #000; font-weight: bold; font-size: 12px; padding: 8px; border-left: 2px solid #000; border-bottom: 2px solid #000; border-right: 2px solid #000; text-align: center;">
                PRECIOS DE PRINCIPALES FERTILIZANTES Y ABONOS ORGÁNICOS (S/.)
            </th>
        </tr>

        <!-- Sección I: Ubicación -->
        <tr>
            <th colspan="12" style="background-color: #FFE082; border: 2px solid #000; font-weight: bold; font-size: 11px; padding: 5px; text-align: left;">
                I. Ubicación política
            </th>
        </tr>
        <tr>
            <th colspan="12" style="border: 2px solid #000; border-top: 1px solid #000; font-size: 9px; padding: 5px; text-align: left;">
                Región: {{ $filtros['region'] ?? 'N/A' }} | Provincia: {{ $filtros['provincia'] ?? 'N/A' }} | Distrito: {{ $filtros['distrito'] ?? 'N/A' }}
            </th>
        </tr>

        <!-- Sección II: Periodo -->
        <tr>
            <th colspan="12" style="background-color: #FFE082; border: 2px solid #000; border-top: 1px solid #000; font-weight: bold; font-size: 11px; padding: 5px; text-align: left;">
                II. Año y mes de referencia
            </th>
        </tr>
        <tr>
            <th colspan="12" style="border: 2px solid #000; border-top: 1px solid #000; font-size: 9px; padding: 5px; text-align: left;">
                Año: {{ $filtros['ano'] ?? 'N/A' }} | Mes: {{ $filtros['mes_nombre'] ?? 'N/A' }}
            </th>
        </tr>

        <!-- Sección III: Información de precios -->
        <tr>
            <th colspan="12" style="background-color: #FFE082; border: 2px solid #000; border-top: 1px solid #000; font-weight: bold; font-size: 11px; padding: 5px; text-align: left;">
                III. Información de precios de principales fertilizantes y abonos orgánicos
            </th>
        </tr>

        <!-- Encabezados de tabla -->
        <tr>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">TIPO</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">FERTILIZANTE/ABONO</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">ENVASE</th>
            @php
                $primerRegistro = $datos->first();
            @endphp
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center;">{{ $primerRegistro->casa_comercial_1 ?? 'Casa 1' }}</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center;">{{ $primerRegistro->casa_comercial_2 ?? 'Casa 2' }}</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center;">{{ $primerRegistro->casa_comercial_3 ?? 'Casa 3' }}</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center;">{{ $primerRegistro->casa_comercial_4 ?? 'Casa 4' }}</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center;">{{ $primerRegistro->casa_comercial_5 ?? 'Casa 5' }}</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center;">{{ $primerRegistro->casa_comercial_6 ?? 'Casa 6' }}</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center;">{{ $primerRegistro->casa_comercial_7 ?? 'Casa 7' }}</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 8px; padding: 5px; text-align: center;">{{ $primerRegistro->casa_comercial_8 ?? 'Casa 8' }}</th>
            <th style="background-color: #8B4513; color: #FFF; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">PRECIO PROMEDIO (S/.)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $categorias = [
                'NITROGENADOS' => 'Nitrogenados',
                'FOSFATADOS' => 'Fosfatados',
                'POTÁSICOS' => 'Potásicos',
                'POTASICOS' => 'Potásicos',
                'COMPUESTOS' => 'Compuestos',
                'ORGÁNICOS' => 'Orgánicos',
                'ORGANICOS' => 'Orgánicos'
            ];

            $datosAgrupados = $datos->groupBy(function($item) {
                return strtoupper($item->producto_categoria ?? '');
            });

            $ordenCategorias = ['NITROGENADOS', 'FOSFATADOS', 'POTÁSICOS', 'POTASICOS', 'COMPUESTOS', 'ORGÁNICOS', 'ORGANICOS'];
        @endphp

        @foreach($ordenCategorias as $categoriaKey)
            @if(isset($datosAgrupados[$categoriaKey]))
                @php
                    $productosCat = $datosAgrupados[$categoriaKey];
                    $nombreCategoria = $categorias[$categoriaKey] ?? $categoriaKey;
                @endphp

                <!-- Fila de categoría -->
                <tr>
                    <td colspan="12" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; font-size: 10px; padding: 5px; text-align: left; color: #8B4513;">
                        {{ strtoupper($nombreCategoria) }}
                    </td>
                </tr>

                <!-- Productos de la categoría -->
                @foreach($productosCat as $dato)
                <tr>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">{{ $dato->producto_categoria ?? '' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">{{ $dato->producto_nombre ?? '' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px; text-align: center;">{{ $dato->envase_presentacion ?? 'Bolsa 50 kg' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px; text-align: center;">{{ $dato->precio_casa_1 ? 'S/. ' . number_format($dato->precio_casa_1, 2) : '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px; text-align: center;">{{ $dato->precio_casa_2 ? 'S/. ' . number_format($dato->precio_casa_2, 2) : '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px; text-align: center;">{{ $dato->precio_casa_3 ? 'S/. ' . number_format($dato->precio_casa_3, 2) : '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px; text-align: center;">{{ $dato->precio_casa_4 ? 'S/. ' . number_format($dato->precio_casa_4, 2) : '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px; text-align: center;">{{ $dato->precio_casa_5 ? 'S/. ' . number_format($dato->precio_casa_5, 2) : '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px; text-align: center;">{{ $dato->precio_casa_6 ? 'S/. ' . number_format($dato->precio_casa_6, 2) : '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px; text-align: center;">{{ $dato->precio_casa_7 ? 'S/. ' . number_format($dato->precio_casa_7, 2) : '-' }}</td>
                    <td style="border: 1px solid #000; padding: 5px; font-size: 8px; text-align: center;">{{ $dato->precio_casa_8 ? 'S/. ' . number_format($dato->precio_casa_8, 2) : '-' }}</td>
                    <td style="border: 2px solid #000; padding: 5px; font-size: 9px; text-align: center; background-color: #FFF; font-weight: bold;">{{ $dato->precio_promedio ? 'S/. ' . number_format($dato->precio_promedio, 2) : '-' }}</td>
                </tr>
                @endforeach
            @endif
        @endforeach

        <!-- Resumen estadístico -->
        @if(isset($estadisticas))
        <tr>
            <td colspan="12" style="height: 10px; border: none;"></td>
        </tr>
        <tr>
            <th colspan="12" style="background-color: #FFE082; border: 2px solid #000; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                RESUMEN ESTADÍSTICO
            </th>
        </tr>
        <tr>
            <td colspan="6" style="border: 1px solid #000; padding: 5px; font-size: 9px;"><strong>Total de registros:</strong> {{ $estadisticas['total_registros'] ?? 0 }}</td>
            <td colspan="6" style="border: 1px solid #000; padding: 5px; font-size: 9px;"><strong>Precio promedio general:</strong> S/. {{ number_format($estadisticas['precio_promedio_general'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td colspan="6" style="border: 1px solid #000; padding: 5px; font-size: 9px;"><strong>Precio mínimo:</strong> S/. {{ number_format($estadisticas['precio_minimo_general'] ?? 0, 2) }}</td>
            <td colspan="6" style="border: 1px solid #000; padding: 5px; font-size: 9px;"><strong>Precio máximo:</strong> S/. {{ number_format($estadisticas['precio_maximo_general'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td colspan="6" style="border: 1px solid #000; padding: 5px; font-size: 9px;"><strong>Productos distintos:</strong> {{ $estadisticas['tipos_distintos'] ?? 0 }}</td>
            <td colspan="6" style="border: 1px solid #000; padding: 5px; font-size: 9px;"><strong>Categorías:</strong> {{ $estadisticas['categorias_distintas'] ?? 0 }}</td>
        </tr>
        @endif

        <!-- Fecha de generación -->
        <tr>
            <td colspan="12" style="border-top: 2px solid #000; padding: 8px; font-size: 8px; text-align: right; font-style: italic;">
                Generado: {{ $fecha_generacion ?? now()->format('d/m/Y H:i') }}
            </td>
        </tr>
    </tbody>
</table>
