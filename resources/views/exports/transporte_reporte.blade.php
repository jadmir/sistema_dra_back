<table>
    <thead>
        <!-- ENCABEZADO -->
        <tr>
            <th colspan="2" rowspan="3" style="background-color: #FFC107; border: 2px solid #000;">
                <strong style="font-size: 16px;">¡SIEA</strong><br>
                <span style="font-size: 8px;">Sistema Integrado de Estadística Agraria</span>
            </th>
            <th colspan="9" style="background-color: #FFC107; border: 2px solid #000; text-align: center;">
                <strong style="font-size: 11px;">Dirección General de Estadística, Seguimiento y Evaluación de Políticas-DGESEP</strong><br>
                <span style="font-size: 9px;">Dirección de Estadística e Información Agraria - DEIA</span><br>
                <strong style="font-size: 12px;">PRECIOS (S/.) DE TRANSPORTE DE PRODUCTOS AGROPECUARIOS Y AGROINDUSTRIALES ALIMENTICIOS</strong>
            </th>
            <th rowspan="3" style="background-color: #FFC107; border: 3px solid #8B4513; text-align: center; vertical-align: middle;">
                <strong style="font-size: 36px; color: #8B4513;">F-14</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <!-- UBICACIÓN POLÍTICA -->
        <tr>
            <th colspan="5" style="background-color: #FFF3E0; border: 1px solid #333; text-align: left; padding: 5px;">
                <strong>I. Ubicación política</strong>
            </th>
            <th colspan="7" style="background-color: #FFF3E0; border: 1px solid #333; text-align: left; padding: 5px;">
                <strong>II. Año y mes de referencia</strong>
            </th>
        </tr>
        <tr>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">1. Región</th>
            <td colspan="4" style="border: 1px solid #333;">{{ $filtros['region'] }}</td>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">Año</th>
            <td colspan="6" style="border: 1px solid #333;">{{ $filtros['anio'] }}</td>
        </tr>
        <tr>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">2. Provincia</th>
            <td colspan="4" style="border: 1px solid #333;">{{ $filtros['provincia'] }}</td>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">Mes</th>
            <td colspan="6" style="border: 1px solid #333;">{{ $filtros['mes'] }} - {{ $filtros['mes_nombre'] }}</td>
        </tr>
        <tr>
            <th style="background-color: #FFFDE7; border: 1px solid #333;">3. Distrito</th>
            <td colspan="11" style="border: 1px solid #333;">{{ $filtros['distrito'] }}</td>
        </tr>

        <!-- RESUMEN ESTADÍSTICO -->
        <tr>
            <th colspan="12" style="background-color: #FFC107; border: 2px solid #8B4513; text-align: center; padding: 8px;">
                <strong style="font-size: 11px;">RESUMEN ESTADÍSTICO DEL PERÍODO</strong>
            </th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #E3F2FD; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Total Encuestas</strong><br>
                <span style="font-size: 14px; color: #2196F3;">{{ $estadisticas['total_encuestas'] }}</span>
            </th>
            <th colspan="2" style="background-color: #E8F5E9; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Total Registros</strong><br>
                <span style="font-size: 14px; color: #4CAF50;">{{ $estadisticas['total_registros'] }}</span>
            </th>
            <th colspan="2" style="background-color: #FFF3E0; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Rutas Distintas</strong><br>
                <span style="font-size: 14px; color: #FF9800;">{{ $estadisticas['rutas_distintas'] }}</span>
            </th>
            <th colspan="2" style="background-color: #F3E5F5; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Precio Promedio</strong><br>
                <span style="font-size: 14px; color: #9C27B0;">S/. {{ number_format($estadisticas['precio_promedio'], 2) }}</span>
            </th>
            <th colspan="2" style="background-color: #E1F5FE; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Dentro Región</strong><br>
                <span style="font-size: 14px; color: #0288D1;">{{ $estadisticas['total_dentro_region'] }}</span>
            </th>
            <th colspan="2" style="background-color: #FFF8E1; border: 1px solid #333; text-align: center; padding: 5px;">
                <strong>Fuera Región</strong><br>
                <span style="font-size: 14px; color: #F57C00;">{{ $estadisticas['total_fuera_region'] }}</span>
            </th>
        </tr>

        <!-- ENCABEZADO TABLA PRINCIPAL -->
        <tr>
            <th colspan="12" style="background-color: #FFC107; border: 2px solid #8B4513; text-align: center; padding: 5px;">
                <strong>III. Información de precios de transporte de productos</strong>
            </th>
        </tr>
        <tr>
            <th style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">Tipo</th>
            <th colspan="2" style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">Origen</th>
            <th colspan="2" style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">Destino</th>
            <th style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">Vía:<br>a. Terrestre<br>b. Fluvial<br>c. Aéreo</th>
            <th colspan="2" style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">Producto<br>(Agrícola,<br>Agroindustrial,<br>Pecuario)</th>
            <th style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">Unidad de<br>medida</th>
            <th style="background-color: #E8F5E9; border: 1px solid #333; text-align: center; font-weight: bold;">Precio con IGV<br>(S/. x UM) -<br>Mínimo</th>
            <th style="background-color: #E8F5E9; border: 1px solid #333; text-align: center; font-weight: bold;">Precio con IGV<br>(S/. x UM) -<br>Máximo</th>
            <th style="background-color: #FFE082; border: 1px solid #333; text-align: center; font-weight: bold;">N° REG.</th>
        </tr>

        <!-- DENTRO DE LA REGIÓN -->
        <tr>
            <th colspan="12" style="background-color: #FFF3E0; border: 2px solid #8B4513; text-align: left; font-weight: bold; padding: 5px;">
                Dentro de la región:
            </th>
        </tr>
        @if($dentro_region->isNotEmpty())
            @foreach($dentro_region as $ruta)
            <tr>
                <td style="border: 1px solid #333; text-align: center; font-size: 9px;">
                    {{ $ruta['tipo'] }}
                </td>
                <td colspan="2" style="border: 1px solid #333; text-align: left;">
                    {{ $ruta['origen'] }}
                </td>
                <td colspan="2" style="border: 1px solid #333; text-align: left;">
                    {{ $ruta['destino'] }}
                </td>
                <td style="border: 1px solid #333; text-align: center; font-size: 9px;">
                    {{ $ruta['via'] }}
                </td>
                <td colspan="2" style="border: 1px solid #333; text-align: left; font-weight: bold;">
                    {{ $ruta['producto'] }}
                </td>
                <td style="border: 1px solid #333; text-align: center; font-size: 9px;">
                    {{ $ruta['unidad_medida'] }}
                </td>
                <td style="border: 1px solid #333; text-align: right; background-color: #E8F5E9;">
                    S/. {{ number_format($ruta['precio_minimo'], 2) }}
                </td>
                <td style="border: 1px solid #333; text-align: right; background-color: #E8F5E9;">
                    S/. {{ number_format($ruta['precio_maximo'], 2) }}
                </td>
                <td style="border: 1px solid #333; text-align: center; font-weight: bold;">
                    {{ $ruta['num_registros'] }}
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="12" style="border: 1px solid #333; text-align: center; padding: 15px; color: #999;">
                    No hay rutas dentro de la región registradas para este período
                </td>
            </tr>
        @endif

        <!-- FUERA DE LA REGIÓN -->
        <tr>
            <th colspan="12" style="background-color: #FFF3E0; border: 2px solid #8B4513; text-align: left; font-weight: bold; padding: 5px;">
                Fuera de la región:
            </th>
        </tr>
        @if($fuera_region->isNotEmpty())
            @foreach($fuera_region as $ruta)
            <tr>
                <td style="border: 1px solid #333; text-align: center; font-size: 9px;">
                    {{ $ruta['tipo'] }}
                </td>
                <td colspan="2" style="border: 1px solid #333; text-align: left;">
                    {{ $ruta['origen'] }}
                </td>
                <td colspan="2" style="border: 1px solid #333; text-align: left;">
                    {{ $ruta['destino'] }}
                </td>
                <td style="border: 1px solid #333; text-align: center; font-size: 9px;">
                    {{ $ruta['via'] }}
                </td>
                <td colspan="2" style="border: 1px solid #333; text-align: left; font-weight: bold;">
                    {{ $ruta['producto'] }}
                </td>
                <td style="border: 1px solid #333; text-align: center; font-size: 9px;">
                    {{ $ruta['unidad_medida'] }}
                </td>
                <td style="border: 1px solid #333; text-align: right; background-color: #E8F5E9;">
                    S/. {{ number_format($ruta['precio_minimo'], 2) }}
                </td>
                <td style="border: 1px solid #333; text-align: right; background-color: #E8F5E9;">
                    S/. {{ number_format($ruta['precio_maximo'], 2) }}
                </td>
                <td style="border: 1px solid #333; text-align: center; font-weight: bold;">
                    {{ $ruta['num_registros'] }}
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="12" style="border: 1px solid #333; text-align: center; padding: 15px; color: #999;">
                    No hay rutas fuera de la región registradas para este período
                </td>
            </tr>
        @endif

        <!-- OBSERVACIONES -->
        <tr>
            <th colspan="12" style="background-color: #FFFDE7; border: 1px solid #333; text-align: left; padding: 8px; font-size: 12px;">
                <strong>IV. Observaciones</strong>
            </th>
        </tr>
        <tr>
            <td colspan="12" style="border: 1px solid #333; min-height: 60px; vertical-align: top; padding: 8px; font-size: 10px;">
                - Total de rutas registradas: {{ $estadisticas['rutas_distintas'] }}<br>
                - Costo promedio general: S/. {{ number_format($estadisticas['precio_promedio'], 2) }}<br>
                - Rango de costos: S/. {{ number_format(min(array_merge($dentro_region->isEmpty() ? [0] : $dentro_region->pluck('precio_minimo')->toArray(), $fuera_region->isEmpty() ? [0] : $fuera_region->pluck('precio_minimo')->toArray())), 2) }} - S/. {{ number_format(max(array_merge($dentro_region->isEmpty() ? [0] : $dentro_region->pluck('precio_maximo')->toArray(), $fuera_region->isEmpty() ? [0] : $fuera_region->pluck('precio_maximo')->toArray())), 2) }}
            </td>
        </tr>

        <!-- ENCUESTADOR Y SUPERVISOR -->
        <tr>
            <th colspan="12" style="background-color: #FFFDE7; border: 2px solid #333; text-align: left; padding: 8px; font-size: 12px; font-weight: bold;">
                V. Del encuestador y supervisor
            </th>
        </tr>
        <tr>
            <th colspan="1" style="background-color: #E8F5E9; border: 1px solid #333; text-align: center; padding: 8px; font-weight: bold; font-size: 11px;">
                ENCUESTADOR
            </th>
            <td colspan="5" style="border: 1px solid #333; padding: 5px;"></td>
            <th colspan="1" style="background-color: #FFF3E0; border: 1px solid #333; text-align: center; padding: 8px; font-weight: bold; font-size: 11px;">
                SUPERVISOR
            </th>
            <td colspan="5" style="border: 1px solid #333; padding: 5px;"></td>
        </tr>
        <tr>
            <td colspan="1" style="border: 1px solid #333; padding: 6px; font-weight: bold; background-color: #F5F5F5;">Nombres:</td>
            <td colspan="5" style="border: 1px solid #333; padding: 6px; font-size: 11px;">
                {{ $filtros['encuestador_nombre'] ?? '......................................' }}
            </td>
            <td colspan="1" style="border: 1px solid #333; padding: 6px; font-weight: bold; background-color: #F5F5F5;">Nombres:</td>
            <td colspan="5" style="border: 1px solid #333; padding: 6px; font-size: 11px;">
                {{ $filtros['supervisor_nombre'] ?? '......................................' }}
            </td>
        </tr>
        <tr>
            <td colspan="1" style="border: 1px solid #333; padding: 6px; font-weight: bold; background-color: #F5F5F5;">Apellidos:</td>
            <td colspan="5" style="border: 1px solid #333; padding: 6px; font-size: 11px;">
                {{ $filtros['encuestador_apellido'] ?? '......................................' }}
            </td>
            <td colspan="1" style="border: 1px solid #333; padding: 6px; font-weight: bold; background-color: #F5F5F5;">Apellidos:</td>
            <td colspan="5" style="border: 1px solid #333; padding: 6px; font-size: 11px;">
                {{ $filtros['supervisor_apellido'] ?? '......................................' }}
            </td>
        </tr>
        <tr>
            <td colspan="1" style="border: 1px solid #333; padding: 6px; font-weight: bold; background-color: #F5F5F5;">Cargo:</td>
            <td colspan="5" style="border: 1px solid #333; padding: 6px; font-size: 11px;">
                {{ $filtros['encuestador_cargo'] ?? '......................................' }}
            </td>
            <td colspan="1" style="border: 1px solid #333; padding: 6px; font-weight: bold; background-color: #F5F5F5;">Cargo:</td>
            <td colspan="5" style="border: 1px solid #333; padding: 6px; font-size: 11px;">
                {{ $filtros['supervisor_cargo'] ?? '......................................' }}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="border: 1px solid #333; padding: 5px;"></td>
            <td colspan="3" style="border: 1px solid #333; padding: 6px; font-weight: bold; background-color: #F5F5F5;">Fecha de supervisión:</td>
            <td colspan="3" style="border: 1px solid #333; padding: 6px; font-size: 11px;">
                {{ !empty($filtros['fecha_validacion']) ? \Carbon\Carbon::parse($filtros['fecha_validacion'])->format('d/m/Y') : '....../....../......' }}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="border: 1px solid #333; padding: 5px;"></td>
            <td colspan="1" style="border: 1px solid #333; padding: 6px; font-weight: bold; background-color: #F5F5F5;">Firma:</td>
            <td colspan="5" style="border: 1px solid #333; padding: 15px; background-color: #FAFAFA;">_______________________________</td>
        </tr>

        <!-- ESPACIADO -->
        <tr>
            <td colspan="12" style="border: none; padding: 5px;"></td>
        </tr>

        <!-- FOOTER -->
        <tr>
            <td colspan="12" style="border: none; text-align: right; padding: 5px; font-size: 8px; color: #666;">
                F7-EISA-DGESEP-DEIA
            </td>
        </tr>
    </tbody>
</table>
