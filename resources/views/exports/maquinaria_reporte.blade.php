<table>
    <thead>
        <!-- ENCABEZADO PRINCIPAL -->
        <tr>
            <th colspan="1" style="background-color: #FFC107; color: #8B4513; font-weight: bold; font-size: 20px; padding: 8px; border: 2px solid #000; text-align: left;">
                ¡SIEA
            </th>
            <th colspan="7" style="background-color: #FFC107; color: #8B4513; font-weight: bold; font-size: 11px; padding: 8px; border: 2px solid #000; border-left: none; text-align: center;">
                Dirección General de Estadística, Seguimiento y Evaluación de Políticas - DGESEP
            </th>
            <th rowspan="3" style="background-color: #FFC107; color: #8B4513; font-weight: bold; font-size: 28px; padding: 8px; border: 2px solid #8B4513; text-align: center; vertical-align: middle;">
                F-1
            </th>
        </tr>
        <tr>
            <th colspan="8" style="background-color: #FFC107; color: #000; font-weight: bold; font-size: 10px; padding: 5px; border-left: 2px solid #000; border-right: 2px solid #000; text-align: center;">
                Dirección de Estadística e Información Agraria - DEIA
            </th>
        </tr>
        <tr>
            <th colspan="8" style="background-color: #FFC107; color: #000; font-weight: bold; font-size: 12px; padding: 8px; border-left: 2px solid #000; border-bottom: 2px solid #000; text-align: center;">
                REPORTE DE PRECIOS DE ALQUILER DE MAQUINARIA AGRÍCOLA (S/.)
            </th>
        </tr>

        <!-- Espacio -->
        <tr><th colspan="9" style="height: 5px;"></th></tr>

        <!-- INFORMACIÓN DEL REPORTE -->
        <tr>
            <th colspan="4" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; padding: 5px; text-align: left;">
                REGIÓN:
            </th>
            <th colspan="5" style="border: 1px solid #000; padding: 5px; text-align: left;">
                {{ $filtros['region'] }}
            </th>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; padding: 5px; text-align: left;">
                PROVINCIA:
            </th>
            <th colspan="5" style="border: 1px solid #000; padding: 5px; text-align: left;">
                {{ $filtros['provincia'] }}
            </th>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; padding: 5px; text-align: left;">
                PERÍODO:
            </th>
            <th colspan="5" style="border: 1px solid #000; padding: 5px; text-align: left;">
                {{ $filtros['mes_nombre'] }} {{ $filtros['anio'] }}
            </th>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; padding: 5px; text-align: left;">
                FECHA DE GENERACIÓN:
            </th>
            <th colspan="5" style="border: 1px solid #000; padding: 5px; text-align: left;">
                {{ $fecha_generacion }}
            </th>
        </tr>

        <!-- Espacio -->
        <tr><th colspan="9" style="height: 10px;"></th></tr>

        <!-- RESUMEN ESTADÍSTICO -->
        <tr>
            <th colspan="9" style="background-color: #FFC107; border: 2px solid #000; font-weight: bold; font-size: 11px; padding: 5px; text-align: center;">
                RESUMEN ESTADÍSTICO
            </th>
        </tr>
        <tr>
            <th colspan="3" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; padding: 5px; text-align: center;">
                Total Encuestas
            </th>
            <th colspan="2" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; padding: 5px; text-align: center;">
                Total Registros
            </th>
            <th colspan="2" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; padding: 5px; text-align: center;">
                Tipos Distintos
            </th>
            <th colspan="2" style="background-color: #FFFDE7; border: 1px solid #000; font-weight: bold; padding: 5px; text-align: center;">
                Precio Promedio
            </th>
        </tr>
        <tr>
            <th colspan="3" style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 14px; font-weight: bold; color: #2196F3;">
                {{ $estadisticas['total_encuestas'] }}
            </th>
            <th colspan="2" style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 14px; font-weight: bold; color: #4CAF50;">
                {{ $estadisticas['total_registros'] }}
            </th>
            <th colspan="2" style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 14px; font-weight: bold; color: #FF9800;">
                {{ $estadisticas['tipos_distintos'] }}
            </th>
            <th colspan="2" style="border: 1px solid #000; padding: 5px; text-align: center; font-size: 14px; font-weight: bold; color: #9C27B0;">
                S/. {{ number_format($estadisticas['precio_promedio'], 2) }}
            </th>
        </tr>

        <!-- Espacio -->
        <tr><th colspan="9" style="height: 10px;"></th></tr>

        <!-- TABLA DE PRECIOS POR TIPO DE MAQUINARIA -->
        <tr>
            <th colspan="9" style="background-color: #FFC107; border: 2px solid #000; font-weight: bold; font-size: 11px; padding: 5px; text-align: center;">
                DETALLE DE PRECIOS POR TIPO DE MAQUINARIA
            </th>
        </tr>
        <tr>
            <th style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                #
            </th>
            <th colspan="2" style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                NOMBRE MAQUINARIA
            </th>
            <th style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                CATEGORÍA
            </th>
            <th style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                PRECIO<br>PROMEDIO
            </th>
            <th style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                PRECIO<br>MÍNIMO
            </th>
            <th style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                PRECIO<br>MÁXIMO
            </th>
            <th style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                DESV.<br>ESTÁNDAR
            </th>
            <th style="background-color: #FFE082; border: 1px solid #000; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                N° REG.
            </th>
        </tr>
    </thead>
    <tbody>
        @php $contador = 1; @endphp
        @foreach($datos as $item)
        <tr>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">
                {{ $contador++ }}
            </td>
            <td colspan="2" style="border: 1px solid #000; padding: 5px; text-align: left;">
                {{ $item['nombre'] }}
            </td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; background-color: #FFF3E0;">
                {{ $item['categoria'] }}
            </td>
            <td style="border: 1px solid #000; padding: 5px; text-align: right; font-weight: bold; background-color: #E8F5E9;">
                S/. {{ number_format($item['precio_promedio'], 2) }}
            </td>
            <td style="border: 1px solid #000; padding: 5px; text-align: right; background-color: #E3F2FD;">
                S/. {{ number_format($item['precio_minimo'], 2) }}
            </td>
            <td style="border: 1px solid #000; padding: 5px; text-align: right; background-color: #FCE4EC;">
                S/. {{ number_format($item['precio_maximo'], 2) }}
            </td>
            <td style="border: 1px solid #000; padding: 5px; text-align: right;">
                {{ number_format($item['desviacion_estandar'], 2) }}
            </td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold;">
                {{ $item['num_registros'] }}
            </td>
        </tr>
        @endforeach

        <!-- Espacio -->
        <tr><th colspan="9" style="height: 10px;"></th></tr>

        <!-- TOTALES -->
        <tr>
            <th colspan="4" style="background-color: #FFE082; border: 2px solid #000; font-weight: bold; padding: 5px; text-align: right;">
                TOTALES:
            </th>
            <th style="background-color: #E8F5E9; border: 2px solid #000; font-weight: bold; padding: 5px; text-align: right;">
                S/. {{ number_format($estadisticas['precio_promedio'], 2) }}
            </th>
            <th style="background-color: #E3F2FD; border: 2px solid #000; font-weight: bold; padding: 5px; text-align: right;">
                S/. {{ number_format($estadisticas['precio_minimo'], 2) }}
            </th>
            <th style="background-color: #FCE4EC; border: 2px solid #000; font-weight: bold; padding: 5px; text-align: right;">
                S/. {{ number_format($estadisticas['precio_maximo'], 2) }}
            </th>
            <th style="border: 2px solid #000; padding: 5px;"></th>
            <th style="background-color: #FFF9C4; border: 2px solid #000; font-weight: bold; padding: 5px; text-align: center; font-size: 12px;">
                {{ $estadisticas['total_registros'] }}
            </th>
        </tr>

        <!-- Espacio -->
        <tr><th colspan="9" style="height: 15px;"></th></tr>

        <!-- PIE DE PÁGINA -->
        <tr>
            <td colspan="9" style="font-size: 8px; padding: 5px; text-align: left; font-style: italic; color: #666;">
                * Precios expresados en Soles (S/.) por hora de alquiler<br>
                ** Los precios promedio son calculados a partir de múltiples encuestas realizadas en el período indicado<br>
                *** Desviación estándar: Medida de dispersión de los precios (menor valor indica mayor estabilidad)
            </td>
        </tr>
        <tr>
            <td colspan="9" style="font-size: 9px; padding: 8px; text-align: right; font-weight: bold; background-color: #FFFDE7; border-top: 2px solid #FFC107;">
                SISTEMA DE INFORMACIÓN ESTADÍSTICA AGRARIA - SIEA | F1-EISA-DGESEP-DEIA
            </td>
        </tr>
    </tbody>
</table>
