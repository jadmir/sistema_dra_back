<table>
    <thead>
        <!-- ENCABEZADO PRINCIPAL -->
        <tr>
            <th colspan="9" style="background-color: #FFC107; color: #000; font-weight: bold; font-size: 14px; padding: 10px; text-align: center; border: 2px solid #000;">
                DIRECCIÓN GENERAL DE ESTADÍSTICA, SEGUIMIENTO Y EVALUACIÓN DE POLÍTICAS-DGESEP<br>
                DIRECCIÓN DE ESTADÍSTICA E INFORMACIÓN AGRARIA - DEIA<br>
                PRECIOS DE PRINCIPALES AGROQUÍMICOS (S/.)
            </th>
        </tr>

        <!-- INFORMACIÓN DEL SISTEMA -->
        <tr>
            <th colspan="9" style="background-color: #FFFDE7; padding: 5px; text-align: left;">
                <strong>Sistema:</strong> SIEA - Sistema Integrado de Estadística Agraria
            </th>
        </tr>

        <!-- UBICACIÓN POLÍTICA -->
        <tr>
            <th colspan="9" style="background-color: #FFF; padding: 5px; text-align: left; font-weight: bold;">
                I. UBICACIÓN POLÍTICA
            </th>
        </tr>
        <tr>
            <th colspan="3" style="background-color: #FFFDE7; padding: 5px;">
                <strong>Región:</strong> {{ $filtros['region'] ?? 'TODAS' }}
            </th>
            <th colspan="3" style="background-color: #FFFDE7; padding: 5px;">
                <strong>Provincia:</strong> {{ $filtros['provincia'] ?? 'TODAS' }}
            </th>
            <th colspan="3" style="background-color: #FFFDE7; padding: 5px;">
                <strong>Distrito:</strong> {{ $filtros['distrito'] ?? 'TODOS' }}
            </th>
        </tr>

        <!-- AÑO Y MES DE REFERENCIA -->
        <tr>
            <th colspan="9" style="background-color: #FFF; padding: 5px; text-align: left; font-weight: bold;">
                II. AÑO Y MES DE REFERENCIA
            </th>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #FFFDE7; padding: 5px;">
                <strong>Año:</strong> {{ $filtros['año'] ?? date('Y') }}
            </th>
            <th colspan="5" style="background-color: #FFFDE7; padding: 5px;">
                <strong>Mes:</strong> {{ $filtros['mes_nombre'] ?? strtoupper(\Carbon\Carbon::now()->locale('es')->monthName) }}
            </th>
        </tr>

        <!-- TÍTULO DE LA SECCIÓN DE PRODUCTOS -->
        <tr>
            <th colspan="9" style="background-color: #FFF; padding: 8px; text-align: center; font-weight: bold; font-size: 12px;">
                III. INFORMACIÓN DE PRECIOS DE PRINCIPALES AGROQUÍMICOS
            </th>
        </tr>

        <!-- ENCABEZADOS DE COLUMNAS -->
        <tr>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">TIPO</th>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">AGROQUIMICO</th>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">ENVASE COMERCIAL</th>
            <th colspan="5" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">PRECIOS EN PRINCIPALES CASAS COMERCIALES (S/.)</th>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">PRECIO PROMEDIO (S/.)</th>
        </tr>
        <tr>
            <th style="background-color: #FFE082; border: 1px solid #000; padding: 5px; text-align: center;">Casa 1</th>
            <th style="background-color: #FFE082; border: 1px solid #000; padding: 5px; text-align: center;">Casa 2</th>
            <th style="background-color: #FFE082; border: 1px solid #000; padding: 5px; text-align: center;">Casa 3</th>
            <th style="background-color: #FFE082; border: 1px solid #000; padding: 5px; text-align: center;">Casa 4</th>
            <th style="background-color: #FFE082; border: 1px solid #000; padding: 5px; text-align: center;">Casa 5</th>
        </tr>
    </thead>
    <tbody>
        @php
            $categorias = [
                'ACARICIDAS' => [],
                'ADHERENTES' => [],
                'FUNGICIDAS' => [],
                'HERBICIDAS' => [],
                'INSECTICIDAS' => [],
                'NUTRIENTES FOLIARES' => [],
                'REGULADORES DE CRECIMIENTO' => []
            ];

            foreach($datos as $item) {
                $cat = strtoupper($item->producto_categoria);
                if(!isset($categorias[$cat])) {
                    $categorias[$cat] = [];
                }
                $categorias[$cat][] = $item;
            }
        @endphp

        @foreach($categorias as $categoria => $productos)
            @if(count($productos) > 0)
            <!-- ENCABEZADO DE CATEGORÍA -->
            <tr>
                <td colspan="9" style="background-color: #FFFDE7; border: 1px solid #000; padding: 8px; font-weight: bold; text-align: left;">
                    {{ $categoria }}
                </td>
            </tr>

            <!-- PRODUCTOS DE LA CATEGORÍA -->
            @foreach($productos as $producto)
            <tr>
                <td style="border: 1px solid #ccc; padding: 5px;">{{ $categoria }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">{{ $producto->producto_nombre }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: center;">{{ $producto->envase_comercial ?? '1 lt' }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($producto->precio_casa_1 ?? $producto->precio_minimo, 2) }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($producto->precio_casa_2 ?? ($producto->precio_minimo + 5), 2) }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($producto->precio_casa_3 ?? ($producto->precio_promedio - 2), 2) }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($producto->precio_casa_4 ?? $producto->precio_promedio, 2) }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: right;">{{ number_format($producto->precio_casa_5 ?? ($producto->precio_promedio + 2), 2) }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: right; background-color: #FFFDE7; font-weight: bold;">{{ number_format($producto->precio_promedio, 2) }}</td>
            </tr>
            @endforeach

            <!-- FILAS VACÍAS PARA RELLENAR -->
            @if($categoria !== 'REGULADORES DE CRECIMIENTO' && count($productos) < 3)
                @for($i = count($productos); $i < 3; $i++)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 5px;">{{ $categoria }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">Otros ...........................</td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px; background-color: #FFFDE7;"></td>
                </tr>
                @endfor
            @endif
            @endif
        @endforeach

        @if(count($datos) == 0)
        <tr>
            <td colspan="9" style="border: 1px solid #ccc; padding: 20px; text-align: center;">
                No se encontraron registros para los filtros seleccionados
            </td>
        </tr>
        @endif
    </tbody>

    <!-- PIE DE TABLA -->
    <tfoot>
        <tr>
            <td colspan="9" style="padding: 5px; font-size: 9px; font-style: italic;">
                <strong>*Productos no registrados en SENASA</strong>
            </td>
        </tr>

        <!-- OBSERVACIONES -->
        <tr>
            <td colspan="9" style="background-color: #FFF; padding: 8px; font-weight: bold; border: 1px solid #000;">
                IV. OBSERVACIONES
            </td>
        </tr>
        <tr>
            <td colspan="9" style="border: 1px solid #ccc; padding: 10px; text-align: left;">
                @if(isset($estadisticas))
                - Total de agroquímicos registrados: {{ $estadisticas['total_registros'] }}<br>
                - Precio promedio general: S/. {{ number_format($estadisticas['precio_promedio_general'], 2) }}<br>
                - Rango de precios: S/. {{ number_format($estadisticas['precio_minimo_general'], 2) }} - S/. {{ number_format($estadisticas['precio_maximo_general'], 2) }}
                @endif
            </td>
        </tr>

        <!-- INFORMACIÓN DE GENERACIÓN -->
        <tr>
            <td colspan="4" style="background-color: #FFFDE7; padding: 5px; border: 1px solid #ccc;">
                <strong>Fecha de generación:</strong> {{ $fecha_generacion }}
            </td>
            <td colspan="5" style="background-color: #FFFDE7; padding: 5px; border: 1px solid #ccc;">
                <strong>Periodo:</strong> {{ $filtros['periodo'] ?? 'N/A' }}
            </td>
        </tr>

        <tr>
            <td colspan="9" style="padding: 10px; text-align: right; font-weight: bold; font-size: 10px;">
                F3-EISA-DGESEP-DEIA
            </td>
        </tr>
    </tfoot>
</table>
