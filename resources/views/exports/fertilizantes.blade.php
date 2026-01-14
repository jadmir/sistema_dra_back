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
            <th colspan="11" style="background-color: #FFC107; color: #000; font-weight: bold; font-size: 12px; padding: 8px; border-left: 2px solid #000; border-bottom: 2px solid #000; text-align: center;">
                PRECIOS DE PRINCIPALES FERTILIZANTES Y ABONOS ORGÁNICOS (S/.)
            </th>
        </tr>
        <tr>
            <th colspan="12" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Sección de Ubicación política -->
        <tr>
            <th colspan="6" style="background-color: #FFF; border: 2px solid #000; border-bottom: 1px solid #000; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                I. Ubicación política
            </th>
            <th colspan="6" style="background-color: #FFF; border: 2px solid #000; border-bottom: 1px solid #000; border-left: none; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                II. Año y mes de referencia
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                1. Región
            </th>
            <th colspan="4" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['region'] ?? '................................' }}
            </th>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Año
            </th>
            <th colspan="4" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['anio'] ?? '..................' }}
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                2. Provincia
            </th>
            <th colspan="4" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['provincia'] ?? '................................' }}
            </th>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Mes
            </th>
            <th colspan="4" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['mes'] ?? '..................' }}
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                3. Distrito
            </th>
            <th colspan="4" style="border-right: 2px solid #000; border-top: none; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                {{ $filtros['distrito'] ?? '................................' }}
            </th>
            <th colspan="6" style="border: 2px solid #000; border-top: none; border-left: none;"></th>
        </tr>

        <tr>
            <th colspan="12" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Encabezado de tabla de precios -->
        <tr>
            <th colspan="12" style="background-color: #FFF; border: 2px solid #000; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                III. Información de precios de principales fertilizantes y abonos orgánicos
            </th>
        </tr>

        <!-- Encabezados de columnas principales -->
        <tr>
            <th colspan="3" style="border: 2px solid #000; border-top: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                PRODUCTOS
            </th>
            <th colspan="8" style="border: 2px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: center;">
                PRECIOS EN PRINCIPALES CASAS COMERCIALES (S/.)
            </th>
            <th rowspan="2" style="border: 2px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: center; vertical-align: middle;">
                PRECIO<br>PROMEDI<br>O (S/.)
            </th>
        </tr>

        <!-- Subencabezados -->
        <tr>
            <th rowspan="2" style="border: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 8px; padding: 3px; text-align: center; vertical-align: middle;">
                TIPO
            </th>
            <th rowspan="2" style="border: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 8px; padding: 3px; text-align: center; vertical-align: middle;">
                AGROQUIMICO
            </th>
            <th rowspan="2" style="border: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 8px; padding: 3px; text-align: center; vertical-align: middle;">
                ENVASE<br>COMERCIAL
            </th>
            <th colspan="8" style="border: 1px solid #000; background-color: #F5F5F5; font-size: 8px; padding: 3px; text-align: center;">
                .......... .......... .......... .......... .......... .......... .......... ..........
            </th>
        </tr>
    </thead>
    <tbody>
        <!-- 1. FERTILIZANTES QUIMICOS -->
        <tr>
            <th colspan="12" style="border: 2px solid #000; border-top: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                1. FERTILIZANTES QUÍMICOS
            </th>
        </tr>

        <!-- NITROGENADOS -->
        <tr>
            <th style="border: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                NITROGENADOS
            </th>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Nitrato de Amonio</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Sulfato de Amonio</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Urea Agrícola</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Nitro 5</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Otros ................................</td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>

        <!-- FOSFATADOS -->
        <tr>
            <th style="border: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                FOSFATADOS
            </th>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Fosfato Diamonico</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Roca Fosforica</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Superfosfato de Calcio Triple</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Superfosfato Simple</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Otros ................................</td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>

        <!-- POTASICOS -->
        <tr>
            <th style="border: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                POTASICOS
            </th>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Cloruro de Potasio</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Sulfato de Magnesio y Potasio</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Sulfato de Potasio</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Nitrato de Potasio</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Otros ................................</td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>

        <!-- COMPUESTOS -->
        <tr>
            <th style="border: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                COMPUESTOS
            </th>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Abono compuesto 12-12-12</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Abono compuesto 15-15-15</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Abono compuesto 20-20-20</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Otros ................................</td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>

        <!-- 2. ABONOS ORGANICOS -->
        <tr>
            <th colspan="12" style="border: 2px solid #000; border-top: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                2. ABONOS ORGÁNICOS
            </th>
        </tr>
        <tr>
            <th style="border: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                ORGANICOS
            </th>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Gallinaza</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Guano de las Islas</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Humus de Lombriz</td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Bolsa x 50 kg</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; font-size: 8px;">Otros ................................</td>
            <td style="border: 1px solid #000; padding: 5px;"></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;">:</td>
            <td style="border: 2px solid #000; padding: 5px;"></td>
        </tr>

        <tr>
            <th colspan="12" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Sección IV. Observaciones -->
        <tr>
            <th colspan="12" style="border: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                IV. Observaciones
            </th>
        </tr>
        <tr>
            <td colspan="12" style="border: 2px solid #000; border-top: none; padding: 20px; height: 60px;"></td>
        </tr>

        <tr>
            <th colspan="12" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Sección V. Del encuestador y supervisor -->
        <tr>
            <th colspan="12" style="border: 2px solid #000; background-color: #FFF; font-weight: bold; font-size: 10px; padding: 5px; text-align: left;">
                V. Del encuestador y supervisor
            </th>
        </tr>
        <tr>
            <th colspan="6" style="border: 2px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                ENCUESTADOR
            </th>
            <th colspan="4" style="border: 2px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                SUPERVISOR
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: 1px solid #000; border-top: 1px solid #000; border-bottom: 1px solid #000; background-color: #FFF; font-weight: bold; font-size: 9px; padding: 5px; text-align: left;">
                Fecha de<br>Supervisión:
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Nombres
            </th>
            <th colspan="4" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                ................................
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: none; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Nombres
            </th>
            <th colspan="2" style="border-right: 1px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                ................................
            </th>
            <th colspan="2" rowspan="3" style="border: 2px solid #000; border-left: 1px solid #000; border-top: none; font-size: 9px; padding: 3px; text-align: left; vertical-align: top;">
                <br><br>Firma
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Apellidos
            </th>
            <th colspan="4" style="border-right: 2px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                ................................
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: none; border-top: none; border-bottom: 1px solid #000; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Apellidos
            </th>
            <th colspan="2" style="border-right: 1px solid #000; border-top: none; border-bottom: 1px solid #000; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
                ................................
            </th>
        </tr>
        <tr>
            <th colspan="2" style="border: 2px solid #000; border-top: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Cargo
            </th>
            <th colspan="4" style="border-right: 2px solid #000; border-top: none; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
            </th>
            <th colspan="2" style="border: 2px solid #000; border-left: none; border-top: none; font-weight: bold; font-size: 9px; padding: 3px; text-align: left;">
                Cargo
            </th>
            <th colspan="2" style="border-right: 1px solid #000; border-top: none; border-left: 1px solid #000; font-size: 9px; padding: 3px; text-align: left;">
            </th>
        </tr>

        <tr>
            <th colspan="12" style="height: 10px; border: none;"></th>
        </tr>

        <!-- Nota al pie -->
        <tr>
            <td colspan="12" style="font-size: 8px; padding: 3px; text-align: right; font-weight: bold;">
                F2-EISA-DGESEP-DEIA
            </td>
        </tr>
    </tbody>
</table>
