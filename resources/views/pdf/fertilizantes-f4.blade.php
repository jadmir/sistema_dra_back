<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario F-4 - Precios de Fertilizantes</title>
    <style>
        @page {
            size: A4 portrait;
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
            min-height: 15px;
            font-size: 8px;
        }
        .precio-cell {
            background: #FFFDE7;
        }
        .categoria-header {
            background: #FFF;
            font-weight: bold;
            border: 2px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 9px;
        }
        .tipo-cell {
            background: #FFF;
            font-weight: bold;
            border: 1px solid #000;
            padding: 3px;
            text-align: left;
            font-size: 9px;
        }
        /* Sección IV - Observaciones */
        .observaciones {
            border: 2px solid #000;
            padding: 10px;
            min-height: 50px;
            background: #FFFDE7;
        }
        /* Sección V - Firmas */
        .firma-section {
            border: 1px solid #000;
            padding: 5px;
            background: #FFF;
        }
        /* Footer */
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
        <div class="codigo-form">F-4</div>
        <div class="header-title">Dirección General de Estadística, Seguimiento y Evaluación de Políticas-DGESEP</div>
        <div class="header-subtitle">Dirección de Estadística e Información Agraria - DEIA</div>
        <div class="header-form-title">PRECIOS DE PRINCIPALES FERTILIZANTES Y ABONOS ORGÁNICOS (S/.)</div>
    </div>

    <div style="height: 10px;"></div>

    <!-- SECCIONES I Y II -->
    <table style="margin-bottom: 10px;">
        <tr>
            <td colspan="6" class="section-header" style="border-right: 1px solid #000;">I. Ubicación política</td>
            <td colspan="6" class="section-header">II. Año y mes de referencia</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">1. Región</td>
            <td colspan="4" class="data-row">{{ $filtros['region'] ?? '................................' }}</td>
            <td colspan="2" class="data-row data-label">Año</td>
            <td colspan="4" class="data-row">{{ $filtros['año'] ?? '..................' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">2. Provincia</td>
            <td colspan="4" class="data-row">{{ $filtros['provincia'] ?? '................................' }}</td>
            <td colspan="2" class="data-row data-label">Mes</td>
            <td colspan="4" class="data-row">{{ $filtros['mes_nombre'] ?? '..................' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">3. Distrito</td>
            <td colspan="4" class="data-row">{{ $filtros['distrito'] ?? '................................' }}</td>
            <td colspan="6" class="data-row"></td>
        </tr>
    </table>

    <div style="height: 10px;"></div>

    <!-- SECCIÓN III - TABLA DE PRECIOS -->
    <table>
        <tr>
            <td colspan="12" class="section-header">III. Información de precios de principales fertilizantes y abonos orgánicos</td>
        </tr>
        <!-- Encabezados principales -->
        <tr>
            <td colspan="3" class="table-header">PRODUCTOS</td>
            <td colspan="8" class="table-header">PRECIOS EN PRINCIPALES CASAS COMERCIALES (S/.)</td>
            <td rowspan="3" class="table-header" style="vertical-align: middle;">PRECIO<br>PROMEDI<br>O (S/.)</td>
        </tr>
        <!-- Subencabezados -->
        <tr>
            <td rowspan="2" class="table-header" style="vertical-align: middle;">TIPO</td>
            <td rowspan="2" class="table-header" style="vertical-align: middle;">AGROQUIMICO</td>
            <td rowspan="2" class="table-header" style="vertical-align: middle;">ENVASE<br>COMERCIAL</td>
            <td colspan="8" class="table-subheader">.......... .......... .......... .......... .......... .......... .......... ..........</td>
        </tr>
        <tr>
            <td class="table-subheader">:</td>
            <td class="table-subheader">:</td>
            <td class="table-subheader">:</td>
            <td class="table-subheader">:</td>
            <td class="table-subheader">:</td>
            <td class="table-subheader">:</td>
            <td class="table-subheader">:</td>
            <td class="table-subheader">:</td>
        </tr>

        <!-- 1. FERTILIZANTES QUÍMICOS -->
        <tr>
            <td colspan="12" class="categoria-header">1. FERTILIZANTES QUÍMICOS</td>
        </tr>

        <!-- NITROGENADOS -->
        <tr>
            <td class="tipo-cell">NITROGENADOS</td>
            <td class="table-cell" style="text-align: left;">Nitrato de Amonio</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Sulfato de Amonio</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Urea Agrícola</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Nitro 5</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Otros ................................</td>
            <td class="table-cell"></td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>

        <!-- FOSFATADOS -->
        <tr>
            <td class="tipo-cell">FOSFATADOS</td>
            <td class="table-cell" style="text-align: left;">Fosfato Diamonico</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Roca Fosforica</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Superfosfato de Calcio Triple</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Superfosfato Simple</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Otros ................................</td>
            <td class="table-cell"></td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>

        <!-- POTASICOS -->
        <tr>
            <td class="tipo-cell">POTASICOS</td>
            <td class="table-cell" style="text-align: left;">Cloruro de Potasio</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Sulfato de Magnesio y Potasio</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Sulfato de Potasio</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Nitrato de Potasio</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Otros ................................</td>
            <td class="table-cell"></td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>

        <!-- COMPUESTOS -->
        <tr>
            <td class="tipo-cell">COMPUESTOS</td>
            <td class="table-cell" style="text-align: left;">Abono compuesto 12-12-12</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Abono compuesto 15-15-15</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Abono compuesto 20-20-20</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Otros ................................</td>
            <td class="table-cell"></td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>

        <!-- 2. ABONOS ORGÁNICOS -->
        <tr>
            <td colspan="12" class="categoria-header">2. ABONOS ORGÁNICOS</td>
        </tr>
        <tr>
            <td class="tipo-cell">ORGANICOS</td>
            <td class="table-cell" style="text-align: left;">Gallinaza</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Guano de las Islas</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Humus de Lombriz</td>
            <td class="table-cell" style="text-align: left;">Bolsa x 50 kg</td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
            <td class="table-cell precio-cell"></td>
        </tr>
        <tr>
            <td class="table-cell"></td>
            <td class="table-cell" style="text-align: left;">Otros ................................</td>
            <td class="table-cell"></td>
            @for($i = 0; $i < 8; $i++)
            <td class="table-cell">:</td>
            @endfor
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
            <td colspan="6" class="section-header" style="border-right: 1px solid #000;">V. Del encuestador y supervisor</td>
            <td colspan="4" class="section-header"></td>
            <td colspan="2" class="section-header"></td>
        </tr>
        <tr>
            <td colspan="6" class="firma-section" style="border-right: 1px solid #000;"><strong>ENCUESTADOR</strong></td>
            <td colspan="4" class="firma-section"><strong>SUPERVISOR</strong></td>
            <td colspan="2" class="firma-section"><strong>Fecha de Supervisión:</strong></td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">Nombres</td>
            <td colspan="4" class="data-row">................................</td>
            <td colspan="2" class="data-row data-label">Nombres</td>
            <td colspan="2" class="data-row">................................</td>
            <td colspan="2" rowspan="3" class="data-row" style="vertical-align: top;"><br><br>Firma</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">Apellidos</td>
            <td colspan="4" class="data-row">................................</td>
            <td colspan="2" class="data-row data-label">Apellidos</td>
            <td colspan="2" class="data-row">................................</td>
        </tr>
        <tr>
            <td colspan="2" class="data-row data-label">Cargo</td>
            <td colspan="4" class="data-row"></td>
            <td colspan="2" class="data-row data-label">Cargo</td>
            <td colspan="2" class="data-row"></td>
        </tr>
    </table>

    <div style="height: 10px;"></div>

    <!-- FOOTER -->
    <table>
        <tr>
            <td class="footer-code">F2-EISA-DGESEP-DEIA</td>
        </tr>
    </table>
</body>
</html>
