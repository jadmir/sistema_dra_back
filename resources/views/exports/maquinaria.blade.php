<table>
    <thead>
        <!-- Encabezado amarillo con título y logo -->
        <tr>
            <th colspan="1" style="background-color: #FFC107; color: #8B4513; font-weight: bold; font-size: 20px; padding: 8px; border: 2px solid #000; text-align: left; vertical-align: middle;">
                ¡SIEA
            </th>
            <th colspan="12" style="background-color: #FFC107; color: #8B4513; font-weight: bold; font-size: 11px; padding: 8px; border: 2px solid #000; border-left: none; text-align: center;">
                Dirección General de Estadística, Seguimiento y Evaluación de Políticas-DGESEP
            </th>
            <th rowspan="3" style="background-color: #FFC107; color: #8B4513; font-weight: bold; font-size: 28px; padding: 8px; border: 2px solid #8B4513; text-align: center; vertical-align: middle; writing-mode: tb-rl; transform: rotate(180deg);">
                F-1
            </th>
        </tr>
        <tr>
            <th colspan="13" style="background-color: #FFC107; color: #000; font-weight: bold; font-size: 10px; padding: 5px; border-left: 2px solid #000; border-right: 2px solid #000; text-align: center;">
                Dirección de Estadística e Información Agraria - DEIA
            </th>
        </tr>
        <tr>
            <th colspan="13" style="background-color: #FFC107; color: #000; font-weight: bold; font-size: 12px; padding: 8px; border-left: 2px solid #000; border-bottom: 2px solid #000; text-align: center;">
                PRECIOS DE ALQUILER DE MAQUINARIA AGRÍCOLA, MANO DE OBRA Y YUNTA (S/.)
            </th>
        </tr>
        <tr>
            <th colspan="14" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Sección de Ubicación política -->
        <tr>
            <th colspan="7" style="background-color: #FFF; border: 2px solid #000; border-bottom: 1px solid #000; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                I. Ubicación política
            </th>
            <th colspan="7" style="background-color: #FFF; border: 2px solid #000; border-bottom: 1px solid #000; border-left: none; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                II. Año y mes de referencia
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                1. Región
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['region'] ?? '................................' }}
            </th>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Año
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['anio'] ?? '..................' }}
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                2. Provincia
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['provincia'] ?? '................................' }}
            </th>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Mes
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['mes'] ?? '..................' }}
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                3. Distrito
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['distrito'] ?? '................................' }}
            </th>
            <th colspan="7" style="border: 2px solid #000; border-top: none; border-left: none;"></th>
        </tr>

        <tr>
            <th colspan="14" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Encabezado de tabla de precios -->
        <tr>
            <th colspan="14" style="background-color: #FFF; border: 2px solid #000; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                III. Información de precios de alquiler de maquinaria agrícola, mano de obra y yunta
            </th>
        </tr>

        <!-- Encabezados de columnas principales -->
        <tr>
            <th rowspan="2" style="border: 2px solid #000; border-top: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: center; vertical-align: middle;">
                1. SECTOR<br>ESTADÍSTICO
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                2. TRACTOR / HORA (S/)*
            </th>
            <th colspan="3" style="border: 2px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                3. OTRA MAQUINARIA / HORA (S/.)
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                4. MANO OBRA/DIA (S/.) **
            </th>
            <th rowspan="2" style="border: 2px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: center; vertical-align: middle;">
                5.<br>YUNTA DIA (S/.)
            </th>
        </tr>

        <!-- Subencabezados -->
        <tr>
            <th style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                ......<br>H.P.
            </th>
            <th style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                ......<br>H.P.
            </th>
            <th style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                ......<br>H.P.
            </th>
            <th style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                ......<br>H.P.
            </th>
            <th style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                3.1<br>Surcadora
            </th>
            <th style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                3.2<br>Cosechadora
            </th>
            <th style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                3.3<br>Trilladora
            </th>
            <th style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                4.1<br>Hombres
            </th>
            <th style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                4.2<br>Mujeres
            </th>
        </tr>
    </thead>
    <tbody>
        <!-- Filas de datos vacías para entrada manual -->
        @for($i = 0; $i < 6; $i++)
        <tr>
            <td style="border: 1px solid #000; padding: 5px; height: 20px;"></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
        </tr>
        @endfor

        <!-- Filas de resumen -->
        <tr>
            <th style="border: 2px solid #000; border-top: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                PRECIO PROMEDIO
            </th>
            <td style="border: 1px solid #000; border-top: 2px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; border-top: 2px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; border-top: 2px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; border-top: 2px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; border-top: 2px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; border-top: 2px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; border-top: 2px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; border-top: 2px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; border-top: 2px solid #000; padding: 5px;"></td>
            <td style="border: 2px solid #000; border-top: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                PRECIO MAXIMO
            </th>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <th style="border: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                PRECIO MINIMO
            </th>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>

        <tr>
            <th colspan="14" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Sección IV. Observaciones -->
        <tr>
            <th colspan="14" style="border: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                IV. Observaciones
            </th>
        </tr>
        <tr>
            <td colspan="14" style="border: 2px solid #000; border-top: none; padding: 20px; height: 60px;"></td>
        </tr>

        <tr>
            <th colspan="14" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Sección V. Del encuestador y supervisor -->
        <tr>
            <th colspan="14" style="border: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                V. Del encuestador y supervisor
            </th>
        </tr>
        <tr>
            <th colspan="7" style="border: 2px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                ENCUESTADOR
            </th>
            <th colspan="7" style="border: 2px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                SUPERVISOR
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Nombres
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                ................................
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: none; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Nombres
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                ................................
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Apellidos
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                ................................
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: none; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Apellidos
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                ................................
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Cargo
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: none; border-top: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Cargo
            </th>
            <th colspan="5" style="border-right: 2px solid #000; border-top: none; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
            </th>
        </tr>

        <tr>
            <th colspan="14" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Notas al pie -->
        <tr>
            <td colspan="14" style="font-size: 8px; padding: 3px; text-align: left;">
                *Hora/Tractor, incluye tractorista
            </td>
        </tr>
        <tr>
            <td colspan="14" style="font-size: 8px; padding: 3px; text-align: left;">
                **Sin incluir almuerzo
            </td>
        </tr>
        <tr>
            <td colspan="14" style="font-size: 8px; padding: 3px; text-align: left;">
                ***Incluye operador
            </td>
        </tr>
        <tr>
            <td colspan="14" style="font-size: 8px; padding: 3px; text-align: right; font-weight: bold;">
                F1-EISA-DGESEP-DEIA
            </td>
        </tr>
    </tbody>
</table>
