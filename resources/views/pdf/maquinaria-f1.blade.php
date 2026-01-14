<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario F-1 - Precios de Maquinaria Agrícola</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        /* Encabezado amarillo con logo y título */
        .header-main {
            background: #FFC107;
            padding: 10px;
            text-align: center;
            position: relative;
            border: 2px solid #000;
        }
        .logo {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            font-weight: bold;
            color: #8B4513;
        }
        .codigo-form {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: #FFC107;
            border: 2px solid #8B4513;
            padding: 8px 12px;
            font-size: 28px;
            font-weight: bold;
            color: #8B4513;
            line-height: 1;
            writing-mode: vertical-rl;
            text-orientation: upright;
        }
        .header-title {
            font-size: 11px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 3px;
        }
        .header-subtitle {
            font-size: 10px;
            font-weight: bold;
            color: #000;
            margin-bottom: 3px;
        }
        .header-form-title {
            font-size: 12px;
            font-weight: bold;
            color: #000;
        }
        /* Secciones I y II */
        .section-header {
            background: #FFFDE7;
            padding: 5px;
            font-weight: bold;
            font-size: 10px;
            border: 2px solid #000;
            text-align: left;
        }
        .data-row {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 9px;
        }
        .data-label {
            font-weight: bold;
            background: #FFF;
        }
        /* Sección III - Tabla de precios */
        .table-header {
            background: #FFE082;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border: 1px solid #000;
            font-size: 9px;
        }
        .table-subheader {
            background: #FFFDE7;
            font-weight: normal;
            text-align: center;
            padding: 3px;
            border: 1px solid #000;
            font-size: 8px;
        }
        .table-cell {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            min-height: 20px;
        }
        .precio-cell {
            background: #FFFDE7;
        }
        /* Sección IV - Observaciones */
        .observaciones {
            border: 2px solid #000;
            padding: 10px;
            min-height: 60px;
            background: #FFFDE7;
        }
        /* Sección V - Firmas */
        .firma-section {
            border: 1px solid #000;
            padding: 5px;
            background: #FFF;
        }
        /* Footer */
        .footer-note {
            font-size: 8px;
            padding: 3px;
            text-align: left;
        }
        .footer-code {
            font-size: 8px;
            font-weight: bold;
            text-align: right;
            padding: 3px;
        }
    </style>
</head>
<body>
    <!-- ENCABEZADO -->
    <div class="header-main">
        <div class="logo">¡SIEA</div>
        <div class="codigo-form">F-1</div>
        <div class="header-title">Dirección General de Estadística, Seguimiento y Evaluación de Políticas-DGESEP</div>
        <div class="header-subtitle">Dirección de Estadística e Información Agraria - DEIA</div>
        <div class="header-form-title">PRECIOS DE ALQUILER DE MAQUINARIA AGRÍCOLA, MANO DE OBRA Y YUNTA (S/.)</div>
    </div>

    <div style="height: 10px;"></div>

    <!-- SECCIONES I Y II -->
    <table style="margin-bottom: 10px;">
        <tr>
            <td colspan="7" class="section-header" style="border-right: 1px solid #000;">I. Ubicación política</td>
            <td colspan="7" class="section-header">II. Año y mes de referencia</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">1. Región</td>
            <td colspan="5" class="data-row">{{ $filtros['region'] ?? '................................' }}</td>
            <td colspan="2" class="data-row data-label">Año</td>
            <td colspan="5" class="data-row">{{ $filtros['año'] ?? '..................' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">2. Provincia</td>
            <td colspan="5" class="data-row">{{ $filtros['provincia'] ?? '................................' }}</td>
            <td colspan="2" class="data-row data-label">Mes</td>
            <td colspan="5" class="data-row">{{ $filtros['mes_nombre'] ?? '..................' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">3. Distrito</td>
            <td colspan="5" class="data-row">{{ $filtros['distrito'] ?? '................................' }}</td>
            <td colspan="7" class="data-row"></td>
        </tr>
    </table>

    <div style="height: 10px;"></div>

    <!-- SECCIÓN III - TABLA DE PRECIOS -->
    <table>
        <tr>
            <td colspan="14" class="section-header">III. Información de precios de alquiler de maquinaria agrícola, mano de obra y yunta</td>
        </tr>
        <!-- Encabezados principales -->
        <tr>
            <td rowspan="2" class="table-header" style="vertical-align: middle;">1. SECTOR<br>ESTADÍSTICO</td>
            <td colspan="4" class="table-header">2. TRACTOR / HORA (S/)*</td>
            <td colspan="3" class="table-header">3. OTRA MAQUINARIA / HORA (S/.)</td>
            <td colspan="2" class="table-header">4. MANO OBRA/DIA (S/.) **</td>
            <td rowspan="2" class="table-header" style="vertical-align: middle;">5.<br>YUNTA (S/.)<br>***</td>
        </tr>
        <!-- Subencabezados -->
        <tr>
            <td class="table-subheader">......<br>H.P.</td>
            <td class="table-subheader">......<br>H.P.</td>
            <td class="table-subheader">......<br>H.P.</td>
            <td class="table-subheader">......<br>H.P.</td>
            <td class="table-subheader">3.1<br>Surcadora</td>
            <td class="table-subheader">3.2<br>Cosechadora</td>
            <td class="table-subheader">3.3<br>Trilladora</td>
            <td class="table-subheader">4.1<br>Hombres</td>
            <td class="table-subheader">4.2<br>Mujeres</td>
        </tr>

        <!-- Filas de datos -->
        @if(isset($datos) && $datos->count() > 0)
            @foreach($datos->take(6) as $item)
            <tr>
                <td class="table-cell">{{ $item->producto_categoria ?? '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->hp_1) ? number_format($item->hp_1, 2) : '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->hp_2) ? number_format($item->hp_2, 2) : '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->hp_3) ? number_format($item->hp_3, 2) : '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->hp_4) ? number_format($item->hp_4, 2) : '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->surcadora) ? number_format($item->surcadora, 2) : '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->cosechadora) ? number_format($item->cosechadora, 2) : '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->trilladora) ? number_format($item->trilladora, 2) : '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->mano_hombres) ? number_format($item->mano_hombres, 2) : '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->mano_mujeres) ? number_format($item->mano_mujeres, 2) : '' }}</td>
                <td class="table-cell precio-cell">{{ isset($item->yunta) ? number_format($item->yunta, 2) : '' }}</td>
            </tr>
            @endforeach
            @for($i = $datos->count(); $i < 6; $i++)
            <tr>
                <td class="table-cell"></td>
                <td class="table-cell">:</td>
                <td class="table-cell">:</td>
                <td class="table-cell">:</td>
                <td class="table-cell">:</td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
            </tr>
            @endfor
        @else
            @for($i = 0; $i < 6; $i++)
            <tr>
                <td class="table-cell"></td>
                <td class="table-cell">:</td>
                <td class="table-cell">:</td>
                <td class="table-cell">:</td>
                <td class="table-cell">:</td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
                <td class="table-cell"></td>
            </tr>
            @endfor
        @endif

        <!-- Filas de resumen -->
        <tr>
            <td class="table-cell data-label" style="text-align: left;">PRECIO PROMEDIO</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell data-label" style="text-align: left;">PRECIO MAXIMO</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell data-label" style="text-align: left;">PRECIO MINIMO</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell">:</td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
            <td class="table-cell precio-cell"></td>
        </tr>
    </table>

    <div style="height: 10px;"></div>

    <!-- SECCIÓN IV - OBSERVACIONES -->
    <table style="margin-bottom: 10px;">
        <tr>
            <td class="section-header">IV. Observaciones</td>
        </tr>
        <tr>
            <td class="observaciones">
                @if(isset($estadisticas))
                <strong>Estadísticas Generales:</strong><br>
                Total de registros: {{ $estadisticas['total_registros'] ?? 0 }}<br>
                Precio promedio: S/. {{ number_format($estadisticas['precio_promedio'] ?? 0, 2) }}<br>
                Rango de precios: S/. {{ number_format($estadisticas['precio_minimo'] ?? 0, 2) }} - S/. {{ number_format($estadisticas['precio_maximo'] ?? 0, 2) }}
                @endif
            </td>
        </tr>
    </table>

    <!-- SECCIÓN V - FIRMAS -->
    <table>
        <tr>
            <td colspan="7" class="section-header" style="border-right: 1px solid #000;">V. Del encuestador y supervisor</td>
            <td colspan="7" class="section-header"></td>
        </tr>
        <tr>
            <td colspan="7" class="firma-section" style="border-right: 1px solid #000;"><strong>ENCUESTADOR</strong></td>
            <td colspan="7" class="firma-section"><strong>SUPERVISOR</strong></td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">Nombres</td>
            <td colspan="5" class="data-row">................................</td>
            <td colspan="2" class="data-row data-label">Nombres</td>
            <td colspan="5" class="data-row">................................</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">Apellidos</td>
            <td colspan="5" class="data-row">................................</td>
            <td colspan="2" class="data-row data-label">Apellidos</td>
            <td colspan="5" class="data-row">................................</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">Cargo</td>
            <td colspan="5" class="data-row"></td>
            <td colspan="2" class="data-row data-label">Cargo</td>
            <td colspan="5" class="data-row">Fecha de supervisión:<br>Firma</td>
        </tr>
    </table>

    <div style="height: 10px;"></div>

    <!-- FOOTER -->
    <table>
        <tr>
            <td class="footer-note">*Hora/Tractor, incluye tractorista</td>
        </tr>
        <tr>
            <td class="footer-note">**Sin incluir almuerzo</td>
        </tr>
        <tr>
            <td class="footer-note">***Incluye operador</td>
        </tr>
        <tr>
            <td class="footer-code">F1-EISA-DGESEP-DEIA</td>
        </tr>
    </table>
</body>
</html>
