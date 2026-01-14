<table>
    <thead>
        <!-- ENCABEZADO PRINCIPAL -->
        <tr>
            <th colspan="8" style="background-color: #FFC107; color: #000; font-weight: bold; font-size: 14px; padding: 10px; text-align: center; border: 2px solid #000;">
                DIRECCIÓN GENERAL DE ESTADÍSTICA, SEGUIMIENTO Y EVALUACIÓN DE POLÍTICAS-DGESEP<br>
                DIRECCIÓN DE ESTADÍSTICA E INFORMACIÓN AGRARIA - DEIA<br>
                PRECIOS (S/) DE TRANSPORTE DE PRODUCTOS AGROPECUARIOS Y AGROINDUSTRIALES ALIMENTICIOS
            </th>
        </tr>

        <!-- INFORMACIÓN DEL SISTEMA -->
        <tr>
            <th colspan="8" style="background-color: #FFFDE7; padding: 5px; text-align: left;">
                <strong>Sistema:</strong> SIEA - Sistema Integrado de Estadística Agraria
            </th>
        </tr>

        <!-- UBICACIÓN POLÍTICA -->
        <tr>
            <th colspan="8" style="background-color: #FFF; padding: 5px; text-align: left; font-weight: bold;">
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
            <th colspan="2" style="background-color: #FFFDE7; padding: 5px;">
                <strong>Distrito:</strong> {{ $filtros['distrito'] ?? 'TODOS' }}
            </th>
        </tr>

        <!-- AÑO Y MES DE REFERENCIA -->
        <tr>
            <th colspan="8" style="background-color: #FFF; padding: 5px; text-align: left; font-weight: bold;">
                II. AÑO Y MES DE REFERENCIA
            </th>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #FFFDE7; padding: 5px;">
                <strong>Año:</strong> {{ $filtros['año'] ?? date('Y') }}
            </th>
            <th colspan="4" style="background-color: #FFFDE7; padding: 5px;">
                <strong>Mes:</strong> {{ $filtros['mes_nombre'] ?? strtoupper(\Carbon\Carbon::now()->locale('es')->monthName) }}
            </th>
        </tr>

        <!-- TÍTULO DE LA SECCIÓN -->
        <tr>
            <th colspan="8" style="background-color: #FFF; padding: 8px; text-align: center; font-weight: bold; font-size: 12px;">
                III. INFORMACIÓN DE PRECIOS DE TRANSPORTE DE PRODUCTOS
            </th>
        </tr>

        <!-- ENCABEZADOS DE COLUMNAS -->
        <tr>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">Tipo</th>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">Origen</th>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">Destino</th>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">Vía:<br>a. Terrestre<br>b. Fluvial<br>c. Aéreo</th>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">Producto<br>(Agrícola, Pecuario, Agroindustrial)</th>
            <th rowspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">Unidad de medida</th>
            <th colspan="2" style="background-color: #FFC107; color: #000; border: 1px solid #000; padding: 8px; text-align: center; font-weight: bold;">Precio con IGV (S/. x UM) -</th>
        </tr>
        <tr>
            <th style="background-color: #FFE082; border: 1px solid #000; padding: 5px; text-align: center;">Mínimo</th>
            <th style="background-color: #FFE082; border: 1px solid #000; padding: 5px; text-align: center;">Máximo</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Agrupar por tipo de transporte
            $tiposTransporte = [
                'Dentro de la región:' => [],
                'Fuera de la región:' => []
            ];

            foreach($datos as $item) {
                // Determinar si es dentro o fuera de la región
                $esInterno = (isset($item->es_interno) && $item->es_interno) ||
                            (strpos(strtolower($item->producto_nombre ?? ''), 'región') !== false);
                $grupo = $esInterno ? 'Dentro de la región:' : 'Fuera de la región:';
                $tiposTransporte[$grupo][] = $item;
            }
        @endphp

        @foreach($tiposTransporte as $tipo => $rutas)
            @if(count($rutas) > 0)
            <!-- ENCABEZADO DE TIPO -->
            <tr>
                <td colspan="8" style="background-color: #FFFDE7; border: 1px solid #000; padding: 8px; font-weight: bold; text-align: left;">
                    {{ $tipo }}
                </td>
            </tr>

            <!-- RUTAS DEL TIPO -->
            @foreach($rutas as $ruta)
            @php
                // Extraer origen y destino del nombre del producto
                $nombreRuta = $ruta->producto_nombre;
                $partes = explode(' - ', $nombreRuta);
                $origen = $partes[0] ?? $nombreRuta;
                $destino = $partes[1] ?? '';
                $tipoVehiculo = $ruta->producto_categoria ?? 'Camión';
                $via = 'a. Terrestre';
                $producto = $ruta->producto_transportado ?? 'Productos agrícolas';
                $unidad = $ruta->unidad_medida ?? 'Tonelada';
            @endphp
            <tr>
                <td style="border: 1px solid #ccc; padding: 5px;">{{ $tipoVehiculo }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">{{ $origen }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">{{ $destino }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: center;">{{ $via }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: left;">{{ $producto }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: center;">{{ $unidad }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: right; background-color: #FFFDE7; font-weight: bold;">{{ number_format($ruta->precio_minimo, 2) }}</td>
                <td style="border: 1px solid #ccc; padding: 5px; text-align: right; background-color: #FFFDE7; font-weight: bold;">{{ number_format($ruta->precio_maximo, 2) }}</td>
            </tr>
            @endforeach

            <!-- FILAS VACÍAS PARA RELLENAR -->
            @if(count($rutas) < 4)
                @for($i = count($rutas); $i < 4; $i++)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px; background-color: #FFFDE7;"></td>
                    <td style="border: 1px solid #ccc; padding: 5px; background-color: #FFFDE7;"></td>
                </tr>
                @endfor
            @endif
            @endif
        @endforeach

        @if(count($datos) == 0)
        <tr>
            <td colspan="8" style="border: 1px solid #ccc; padding: 20px; text-align: center;">
                No se encontraron registros para los filtros seleccionados
            </td>
        </tr>
        @endif
    </tbody>

    <!-- PIE DE TABLA -->
    <tfoot>
        <!-- OBSERVACIONES -->
        <tr>
            <td colspan="8" style="background-color: #FFF; padding: 8px; font-weight: bold; border: 1px solid #000;">
                IV. OBSERVACIONES
            </td>
        </tr>
        <tr>
            <td colspan="8" style="border: 1px solid #ccc; padding: 10px; text-align: left;">
                @if(isset($estadisticas))
                - Total de rutas registradas: {{ $estadisticas['total_registros'] ?? count($datos) }}<br>
                - Costo promedio general: S/. {{ number_format($estadisticas['precio_promedio_general'] ?? 0, 2) }}<br>
                - Rango de costos: S/. {{ number_format($estadisticas['precio_minimo_general'] ?? 0, 2) }} - S/. {{ number_format($estadisticas['precio_maximo_general'] ?? 0, 2) }}
                @endif
            </td>
        </tr>

        <!-- INFORMACIÓN DE GENERACIÓN -->
        <tr>
            <td colspan="4" style="background-color: #FFFDE7; padding: 5px; border: 1px solid #ccc;">
                <strong>Fecha de generación:</strong> {{ $fecha_generacion }}
            </td>
            <td colspan="4" style="background-color: #FFFDE7; padding: 5px; border: 1px solid #ccc;">
                <strong>Periodo:</strong> {{ $filtros['periodo'] ?? 'N/A' }}
            </td>
        </tr>

        <tr>
            <td colspan="8" style="padding: 10px; text-align: right; font-weight: bold; font-size: 10px;">
                F7-EISA-DGESEP-DEIA
            </td>
        </tr>
    </tfoot>
</table>
